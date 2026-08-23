# Stock tracking for product items, with kits and assembly

## Context

A simple inventory feature: a stock quantity per item, adjusted automatically by bills and invoices. Written 2026-10-19 after reading the code and stress-testing the sync design. Decisions: tracking is **opt-in per item**; **negative stock is allowed** (shown in red); sold things may be **built from other items**, both as kits consumed at sale and as units assembled ahead; the new strings ship in **all 61 locales**; the work lands as three commits (ledger, UI, API).

Upstream left a hook: `config/type.php` gives every document type an `inventory_stock_action` (`decrease` for invoice/invoice-recurring at lines 131/185, `increase` for bill/bill-recurring at 229/281). Nothing reads it yet.

## Rules

A document counts when: its type has `inventory_stock_action` **and** does not end in `-recurring` (templates never move stock; the invoices they generate do); it is not soft-deleted; its status is not `draft` or `cancelled` (same set as `Document::scopeAccrued`).

Each line of a counting document resolves to (item, quantity) pairs, one level deep:
- line item has `track_stock` on → the item itself, line qty;
- else, if the item has components → each component, line qty × quantity-per-unit, with `via_item_id` = the line item;
- else nothing.
Only pairs whose item has `track_stock` on and the document's `company_id` become movements. Quantity is negated for `decrease`; `moved_at` = `documents.issued_at`.

So a composite item's toggle picks the model: **off + components = kit** (parts leave stock when the invoice is sent); **on + components = assembled ahead** (a Build action turns parts into finished units; invoices consume finished units). An item with components cannot be a component, and a component cannot have components (one level, validated).

Ledger rows: derived rows always carry `document_id`; manual rows (`adjustment`, `assembly`) never do. The sync only deletes and recreates rows by `document_id`. Changing a kit's components or toggling its tracking resyncs every document that sold that kit, so derived rows always reflect the current recipe (adjust afterwards if the old recipe differed; Build gives a frozen snapshot when that matters). Stock on hand = `SUM(quantity)`; no cached column.

Lifecycle: draft invoice → nothing; sent/viewed/partial/paid → `−qty`; edit qty 3→5 → `−5`; cancelled or deleted → rows removed; restored → draft, nothing; bill received → `+qty`; untracked item, service, no item → nothing; kit sold → `−qty × per-unit` for each tracked part.

## Design (verified against the code)

**Observers, not domain events.** `App\Observers\Document` and `App\Observers\DocumentItem` (both `extends App\Abstracts\Observer`, registered in `app/Providers/Observer.php` next to `Transaction::observe(...)`). Every runtime write path uses Eloquent events: `CreateDocument`, `UpdateDocument` (force-deletes lines, recreates them, then `update()`), `DeleteDocument`, `DuplicateDocument` (bkwld/cloner; status forced to draft; items saved via HasMany), `RecurringCheck` (clone, then `save()` with the new type and issued_at), Excel import (`saveOrFail` per row; documents sheet first, lines later, no document re-save), and every status transition (`MarkDocumentSent/Received/Viewed`, `CancelDocument`, `RestoreDocument`, `Create/Update/MatchBankingDocumentTransaction`, `Observers\Transaction::deleted`). Domain events miss Excel import, duplicate and payment-driven status changes, and fire outside the jobs' `DB::transaction`. The one non-event path is company backup restore (`CompanyImporter` uses `DB::table()->insert`); handled by registering the new tables in `CompanyArchive/Schema.php` so rows round-trip as data.

**Observer hooks**
- `Document@saved`: `if ($doc->wasChanged(['status', 'issued_at', 'type'])) Stock::syncDocument($doc->id)`. Sufficient: the line hooks always produce "lines × document row as stored"; only those three columns can invalidate it. `Document@deleted`, `@restored`: sync unconditionally.
- `DocumentItem@saved`: `if (empty($line->document_id)) return; if ($line->wasRecentlyCreated || $line->wasChanged(['item_id', 'quantity', 'document_id'])) sync($line->document_id)`. `@deleted`: sync (fires for soft and force deletes).
- Never `save()` or read relations in the observers: the jobs put non-column attributes on the in-memory models (`paid_amount`, `reconciled`, `item_taxes`, …), and `$this->model->items` is stale inside `UpdateDocument` after `deleteRelationships(..., true)` (`app/Traits/Relationships.php:49-67`).

**`App\Utilities\Stock` uses the query builder only.** `App\Abstracts\Model` is `Cachable` (model cache defaults on unless `.env` disables it) so an Eloquent `find()` inside the observer can serve a stale document; `DocumentItem`'s `retrieved` hook calls `setTaxIds()` → one query per line; `company_id()` is null in console/queue contexts (`RecurringCheck`, queued `CreateDocumentItemsAndTotals`) so the `App\Scopes\Company` scope must not be relied on. Same connection: inside a transaction Laravel routes reads to the write PDO, so uncommitted rows are visible. Wrap in `DB::transaction()` (a savepoint when nested; `MarkDocumentSent` and the Transaction observer run outside any transaction).

```php
public static function syncDocument(int $documentId): void
{
    DB::transaction(function () use ($documentId) {
        DB::table('item_stock_movements')->where('document_id', $documentId)->delete();

        $doc = DB::table('documents')->where('id', $documentId)->first();
        if (! $doc || $doc->deleted_at !== null) return;
        if (! $direction = self::directionFor($doc->type)) return;   // null for *-recurring or no action
        if (in_array($doc->status, ['draft', 'cancelled'])) return;

        $lines = DB::table('document_items')->where('document_id', $documentId)
            ->whereNull('deleted_at')->where('item_id', '>', 0)->get(['id', 'item_id', 'quantity']);
        if ($lines->isEmpty()) return;

        $lineItemIds = $lines->pluck('item_id')->unique()->all();
        $components = DB::table('item_components')->whereIn('item_id', $lineItemIds)
            ->whereNull('deleted_at')->get(['item_id', 'component_id', 'quantity'])->groupBy('item_id');
        $tracked = DB::table('items')->where('company_id', $doc->company_id)
            ->whereIn('id', array_merge($lineItemIds, $components->flatten()->pluck('component_id')->all()))
            ->where('track_stock', true)->pluck('id')->flip();          // bool binding: PostgreSQL-safe

        $rows = []; $now = now();
        foreach ($lines as $line) {
            // tracked item: itself; untracked kit: its parts; anything else: nothing
            $pairs = isset($tracked[$line->item_id])
                ? [[$line->item_id, (float) $line->quantity, null]]
                : ($components[$line->item_id] ?? collect())->map(fn ($c) =>
                    [$c->component_id, (float) $line->quantity * (float) $c->quantity, $line->item_id])->all();
            foreach ($pairs as [$itemId, $qty, $via]) {
                if (! isset($tracked[$itemId])) continue;
                $rows[] = ['company_id' => $doc->company_id, 'item_id' => $itemId, 'via_item_id' => $via,
                    'document_id' => $doc->id, 'document_item_id' => $line->id, 'type' => $doc->type,
                    'quantity' => $direction * $qty, 'moved_at' => $doc->issued_at, 'description' => null,
                    'created_from' => $doc->created_from, 'created_by' => $doc->created_by,
                    'created_at' => $now, 'updated_at' => $now];
            }
        }
        if ($rows) DB::table('item_stock_movements')->insert($rows);
    });
}
// directionFor(type): null if the type ends with '-recurring'; inventory_stock_action increase => 1, decrease => -1, else null
// resyncItem(Item): delete its derived rows; collect distinct document_id from live document_items whose item_id is
//   the item or any kit containing it (item_components.component_id = item); syncDocument() each.
```
Cost: one SELECT + DELETE (+ INSERT) per qualifying save; `CreateDocument` triggers N+1 syncs for N lines (cumulative re-derivation, small N). Optimise only if profiling says so.

**Ledger model must not extend `App\Abstracts\Model`.** It hard-wires `SoftDeletes` (the query would fail on a table without `deleted_at`) and `Cachable`. Follow `app/Models/Common/Company.php:24-26`: `class ItemStockMovement extends Illuminate\Database\Eloquent\Model implements Laratrust\Contracts\Ownable { use HasFactory, Owners, Sources, Tenants; }` plus `ownerKey()` as in `Company.php:683-690`. `ItemComponent` is an ordinary child model like `ItemTax` (`extends App\Abstracts\Model`, soft deletes); the sync reads it with `whereNull('deleted_at')`.

**Item aggregate.** `Item::scopeWithStock($q) => $q->withSum('stock_movements as stock', 'quantity')`, `public $sortableAs = ['stock']` (akaunting/laravel-sortable `orderBy`s aliases listed there), and `getStockQuantityAttribute(): ?float` → `null` when `! track_stock`; else `(float) $this->attributes['stock']` when present, else `(float) $this->stock_movements()->sum('quantity')`. The fallback is a relation **method** call (a query), not a property, so it never trips `preventLazyLoading` (on globally, `app/Providers/App.php:43-53`). Not in `$appends`. Known limit: sorting by `category.name` runs `select(items.*)` in the sortable join and drops the aggregate; the fallback then costs one query per row on that page. Acceptable.

**Manual movements.** `App\Jobs\Common\CreateItemStockAdjustment(Item, $request)`: `quantity` ("set stock to", converted to a delta against the current sum) **or** `change` (delta), `moved_at` (default now), `description` → one `adjustment` row. `App\Jobs\Common\CreateItemAssembly(Item, $request)`: requires `track_stock` on and components; `quantity` (negative = disassemble), `moved_at`, `description` → for each tracked component `−quantity × per-unit` (`type assembly`, `via_item_id` = the item) plus `+quantity` for the item (`type assembly`). `stock_quantity` on the item form/API is honoured only when an item is created with tracking on, or when `track_stock` flips off→on (`App\Observers\Item@saved` runs `Stock::resyncItem` whenever `track_stock` changed, whichever path saved the item; `UpdateItem` then adds the opening adjustment if `filled('stock_quantity')`). On an already-tracked item `stock_quantity` is ignored; use the adjust modal or endpoint. That keeps a GET-then-PATCH client (including `la-update`) from silently reverting sales.

**Components.** `App\Jobs\Common\CreateItemComponent(Item, $request)`: upsert by (item, component) and `Stock::resyncItem($item)`; `DeleteItemComponent(ItemComponent)`: delete, resync the parent. Validation (`App\Http\Requests\Common\ItemComponent`): `component_id required|integer|exists:items,id`, not the item itself, the component must have no components and the item must not be a component of another item (one level), `quantity required|numeric|gt:0`. Any item type may be a component (a labour service in a kit is fine; only tracked parts move stock).

**Permissions.** `assignPermissionsToController()` (`app/Traits/Permissions.php:426-501`) guards only the standard method names, so new methods and controllers are unprotected by default. Every new route gets explicit middleware: `Api\Common\Items::__construct()` calls `parent::__construct()` then `->middleware('permission:read-common-items')->only('stockMovements')` and `update-common-items` for `adjustStock`, `buildStock`, `storeComponent`, `destroyComponent`; the new `Modals\ItemStock`, `Modals\ItemComponents` and `Common\ItemComponents` controllers override the constructor with `update-common-items` on every method (as `Modals\Items` does; never copy `Modals\DocumentTransactions`, whose checks are commented out). No new permission names → no seeder/migration trap.

## PR1 — ledger, sync, components data, jobs, backup (about a day)

New files
- `database/migrations/2026_02_05_000000_core_v3021.php` (style: `2026_02_04_000000_core_v3020.php`):
  - `Schema::table('items')` → `boolean('track_stock')->default(false)`.
  - `Schema::create('item_components')`: `increments('id')`, `integer('company_id')`, `integer('item_id')`, `integer('component_id')`, `double('quantity', 12, 2)`, `string('created_from', 100)->nullable()`, `unsignedInteger('created_by')->nullable()`, `timestamps()`, `softDeletes()`; indexes `['company_id', 'item_id']`, `component_id` (mirror of `item_taxes`, core_v2:298-306).
  - `Schema::create('item_stock_movements')`: `increments('id')`, `integer('company_id')`, `integer('item_id')`, `integer('via_item_id')->nullable()`, `integer('document_id')->nullable()`, `integer('document_item_id')->nullable()`, `string('type', 20)`, `double('quantity', 12, 2)` (matches `document_items.quantity`, core_v307), `dateTime('moved_at')`, `text('description')->nullable()`, `string('created_from', 100)->nullable()`, `unsignedInteger('created_by')->nullable()`, `timestamps()`; indexes `company_id`, `['item_id', 'moved_at']`, `document_id`. **No** `softDeletes()`. `down()` drops both tables and the column.
- `app/Models/Common/ItemStockMovement.php` (see Design): `$table`, `$fillable` (all columns), `$casts` `quantity => double`, `moved_at => datetime`; relations `item()`, `via_item()`, `document()`, `document_item()`; `scopeManual()` (`whereNull('document_id')`), `scopeDerived()`; `newFactory()`.
- `app/Models/Common/ItemComponent.php` (`extends App\Abstracts\Model`, like `ItemTax`): `$fillable = ['company_id', 'item_id', 'component_id', 'quantity', 'created_from', 'created_by']`, `quantity => double`; `item()`, `component()`.
- `database/factories/ItemStockMovement.php` (default an adjustment: `company_id => $this->company->id`, `type 'adjustment'`, `moved_at now()`, `created_from 'core::factory'`; states `forItem(Item)`, `quantity(float)`) and `database/factories/ItemComponent.php`. Both extend `App\Abstracts\Factory`.
- `app/Utilities/Stock.php` — constants `ADJUSTMENT`, `ASSEMBLY`; `syncDocument`, `resyncItem`, `directionFor` (above).
- `app/Observers/Document.php`, `app/Observers/DocumentItem.php` (hooks above), `app/Observers/Item.php` (`saved`: resync when `track_stock` changed).
- `app/Jobs/Common/CreateItemStockAdjustment.php`, `CreateItemAssembly.php`, `CreateItemComponent.php` (all `HasOwner, HasSource, ShouldCreate`), `DeleteItemComponent.php` (`ShouldDelete`).
- `app/Http/Requests/Common/ItemStockAdjustment.php` (`quantity => required_without:change|nullable|numeric`, `change => required_without:quantity|nullable|numeric`, `moved_at => nullable|date`, `description => nullable|string`), `ItemStockBuild.php` (`quantity => required|numeric|not_in:0`, `moved_at`, `description`), `ItemComponent.php` (above).
- `tests/Feature/Common/ItemStockTest.php`, `tests/Feature/Common/ItemComponentsTest.php` (below).

Edited files
- `app/Providers/Observer.php`: register both observers.
- `app/Models/Common/Item.php:38,45`: `track_stock` in `$fillable` and `$casts` (boolean); `stock_movements()`, `components()` (hasMany ItemComponent, with `component`), `component_of()` (hasMany ItemComponent by `component_id`); `scopeWithStock`; `getStockQuantityAttribute`; `public $sortableAs = ['stock']`.
- `app/Http/Requests/Common/Item.php`: `track_stock => nullable|boolean`, `stock_quantity => nullable|numeric` (shared by web, API and Excel import).
- `app/Jobs/Common/CreateItem.php`, `UpdateItem.php`: opening count as in Design, the resync being the Item observer's job (`$fillable` filters `stock_quantity` out of mass assignment).
- `app/Jobs/Common/DeleteItem.php:15`: `deleteRelationships($this->model, ['taxes', 'stock_movements', 'components'])`; `getRelationships()` also blocks deletion while the item is a component of another item (`component_of`).
- `app/Models/Common/Company.php`: `item_stock_movements()`, `item_components()` hasMany. `app/Jobs/Common/DeleteCompany.php:31`: list both first; mute `Observers\Document` and `Observers\DocumentItem` around the transaction in a `try/finally` (do not copy `DeleteDocument`, which skips `unmute()` on exception).
- `app/Utilities/CompanyArchive/Schema.php`: after `item_taxes`, `'item_components' => ['model' => 'App\Models\Common\ItemComponent', 'fks' => ['item_id' => 'items', 'component_id' => 'items']]`; after `document_items`, `'item_stock_movements' => ['model' => 'App\Models\Common\ItemStockMovement', 'fks' => ['item_id' => 'items', 'via_item_id' => 'items', 'document_id' => 'documents', 'document_item_id' => 'document_items']]`. `config/company_backups.php:16` `schema_version => 2` (the new `items.track_stock` column makes new archives unrestorable on older instances; the bump makes `ImportCompany:153-155` refuse them cleanly).

Tests. Helpers: `trackedItem()` = `Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true])`; `invoiceRequest($item, $qty, $state)` = `Document::factory()->invoice()->{$state}()->items()->raw()` with `items.0` overridden; never `Document::factory()->create()` (its `configure()` runs `UpdateDocument` with a random item); count rows with `DB::table('item_stock_movements')`.
- `ItemStockTest`: sent invoice → one row −3 with `document_item_id`, `moved_at`, `company_id`; received bill → +5; draft then `DocumentMarkedSent` → 0 then 1; `CancelDocument` → 0, `RestoreDocument` → still 0; untracked item → 0; `UpdateDocument` qty 3→7 → exactly one row −7; draft→sent **and** qty change in one update → one row (stale-lines + final resync); `issued_at` change updates `moved_at`; `DeleteDocument` → 0; `DuplicateDocument` → clone draft, 0 rows, original keeps 1; recurring template → 0, plus `directionFor` asserts; `PaymentReceived` then `DeleteTransaction` → still exactly 1 row; enabling tracking backfills (invoice 2 + bill 5 → +3), disabling removes derived rows; an adjustment survives a document resync with the same id; sync works with `Company::forgetCurrent()`; import-shaped path (`Document::create` sent, then two `DocumentItem::create`) → 2 rows; `withStock()->orderBy('stock')` matches the accessor and both are floats; `handleLazyLoadingViolationUsing(throw)` guard around the create/update bodies; `DeleteItem` removes adjustments; opening count via `UpdateItem(track_stock=1, stock_quantity=10)` with prior sales → stock 10.
- `ItemComponentsTest`: kit (untracked) with parts A×2 (tracked) and B×1 (untracked) sold qty 3 → one row for A: −6, `via_item_id` = kit, none for B; tracked composite with components sold → one row for itself, none for parts; `CreateItemAssembly` qty 5 → +5 composite, −10 A, nothing for B, all `type assembly`; disassemble −2 reverses; adding/removing/changing a component resyncs an already-sent invoice; enabling tracking on a part backfills past kit sales; validation rejects self, a component that is a kit, and a kit used as a component; `DeleteItem` blocked while the item is a component; backup round trip keeps components and movements (add to `CompanyBackupTest::testItRestoresACompany…`).

## PR2 — items UI and translations (about a day)

- `app/Http/Controllers/Common/Items.php:28`: `Item::with('category', 'media', 'taxes')->withStock()->collect()`. `edit()`: also pass `$components = $item->components()->with('component')->get()`, `$movements = $item->stock_movements()->with(['document', 'via_item'])->orderByDesc('moved_at')->orderByDesc('id')->limit(25)->get()`, and `$candidates` for the components modal (enabled items, excluding the item, kits and items used as components).
- `resources/views/common/items/index.blade.php`: shrink category `w-3/12` → `w-2/12`; add `<x-table.th class="w-1/12" hidden-mobile><x-sortablelink column="stock" title="{{ trans('stock.stock') }}" /></x-table.th>` and a `td` with `stock_quantity` (`text-red` when `< 0`, `<x-empty-data />` when `null`).
- `create.blade.php` / `edit.blade.php`: a **Stock** `<x-form.section v-if="form.type == 'product'">` (precedent `reports/create.blade.php:21`) with `<x-form.group.toggle name="track_stock" :value="$item->track_stock ?? false" />` (posts hidden 0/1, sets `form.track_stock`) and `<x-form.group.number name="stock_quantity" label="{{ trans('stock.opening_count') }}" v-show="form.track_stock == 1" not-required />` — on edit rendered only when `! $item->track_stock`; hint text explaining kit vs assembled. On edit, when tracked: on-hand, the latest movements (date; source = document link via `route(config('type.document.' . $m->type . '.route.prefix') . '.show', $m->document_id)`, "via <kit>" when `via_item_id`, or Adjustment/Build + description; signed qty), an **Adjust stock** `<x-button>` with `@click="onAddPayment('{{ route('modals.items.stock.create', $item->id) }}')"` (generic modal opener in `resources/assets/js/mixins/global.js:712-856`, available through the Global mixin in `items.js`) and, when the item has components, a **Build** button opening `modals.items.stock.build.create`. A **Components** section (edit only, products only): table of components (name, quantity per unit, `<x-delete-button :model="$component" route="items.components.destroy" />` — `DeleteButton` builds the URL from the model id) and an **Add component** button opening `modals.items.components.create`. No JS rebuild needed.
- `app/Models/Common/Item.php` `getLineActionsAttribute` (190-226): when `track_stock`, a `type => 'button'` action "Adjust stock" (icon `inventory`, permission `update-common-items`, `@click => onAddPayment(route('modals.items.stock.create', $this->id))`) — pattern `Document.php:554-564`.
- `app/BulkActions/Common/Items.php`: **Track stock** and **Stop tracking stock** bulk actions (handles `track`/`untrack`, icons `inventory_2`/`layers_clear`, permission `update-common-items`, strings under `stock.bulk.*` and `stock.messages.tracked/untracked`). Each selected item just gets the flag flipped and saved inside a transaction; the Item observer rebuilds its ledger. `track` leaves services alone and flashes `stock.errors.not_product` for each, so the bulk controller reports the count that changed.
- New `app/Http/Controllers/Modals/ItemStock.php` (`create`/`store` for adjustments, `createBuild`/`storeBuild` for assembly; `create` returns `{success, html, data: {title, buttons}}` like `Modals\DocumentTransactions@create:41-91`; `store` → `ajaxDispatch(job)`, `redirect => url()->previous()`, flash), `Modals\ItemComponents.php` (`create`/`store`), `Common\ItemComponents.php` (`destroy`). Explicit constructor middleware on all. Views `resources/views/modals/items/stock.blade.php` (`<x-form id="form-item-stock">` — unique id, `AkauntingModalAddNew.vue:226-228` builds the Form from the first child's id; radio `mode` set/change, `x-form.group.number` for `quantity`/`change` with `v-if` on mode, `x-form.group.date name="moved_at" date-format="Y-m-d"`, textarea `description`, `form-group-class="col-span-6"`), `build.blade.php` (quantity, date, description), `component.blade.php` (`x-form.group.select name="component_id" :options="$candidates"`, number `quantity`).
- `routes/admin.php`: modals group (line 237+) `GET items/{item}/stock/create` → `Modals\ItemStock@create` (`items.stock.create`), `POST items/{item}/stock` (`items.stock.store`), `GET items/{item}/stock/build/create`, `POST items/{item}/stock/build`, `GET items/{item}/components/create`, `POST items/{item}/components`; `common` group `DELETE items/components/{component}` → `Common\ItemComponents@destroy` (`items.components.destroy`, flat so the delete button's single-id URL works).
- `config/search-string.php` Item (182-196): `'track_stock' => ['boolean' => true]`.
- Translations: new `resources/lang/<locale>/stock.php` in all 61 locales (keys: `stock`, `track_stock`, `stock_quantity`, `opening_count`, `adjust`, `adjustment`, `set_to`, `change_by`, `reason`, `movements`, `no_movements`, `via`, `components`, `add_component`, `quantity_per_unit`, `build`, `assembly`, `form_description.stock`, `form_description.components`, `messages.adjusted`, `messages.built`, `errors.component_is_self`, `errors.component_is_kit`, `errors.kit_as_component`, `errors.not_tracked`), plus `search_string.columns.track_stock` in each locale's `search_string.php` (the boolean filter label resolves from `general.`/`search_string.columns.`, `app/View/Components/SearchString.php:140-153`). Precedent: `company_backups.php` shipped all locales in one commit (1d7ac2b8e).
- Tests (`tests/Feature/Common/ItemsTest.php` additions): index shows the stock column; edit with `track_stock=1, stock_quantity=10` creates the opening adjustment; stock modal `store` with `quantity` and with `change`; build modal creates the assembly rows; components modal adds a row and `destroy` removes it and resyncs.

## PR3 — API, skill, backup/export (about half a day)

- `app/Http/Resources/Common/Item.php`: `'track_stock' => (bool) $this->track_stock`, `'stock_quantity' => $this->stock_quantity` (float or null; the accessor casts — PostgreSQL returns sums as strings), `'components' => [static::$wrap => ItemComponent::collection($this->components)]`.
- New resources `ItemStockMovement` (id, item_id, via_item_id, document_id, document_item_id, type, quantity (float), moved_at ISO, description, created_*) and `ItemComponent` (id, item_id, component_id, name of the component, quantity (float)).
- `app/Http/Controllers/Api/Common/Items.php`: constructor middleware (Design); `index`/`show` → `with('category', 'media', 'taxes.tax', 'components.component')->withStock()`; `stockMovements(Item)` → `$item->stock_movements()->with('via_item')->orderByDesc('moved_at')->orderByDesc('id')->paginate(request('limit', 25))`; `adjustStock(Item, ItemStockAdjustment)`, `buildStock(Item, ItemStockBuild)`, `storeComponent(Item, ItemComponent)`, `destroyComponent(Item, ItemComponent)` → the jobs; `created(...)`/`noContent()` responses as the file does today.
- `routes/api.php:31-34`, before `apiResource`: `GET items/{item}/stock-movements`, `POST items/{item}/stock-adjustments`, `POST items/{item}/stock-builds`, `POST items/{item}/components`, `DELETE items/{item}/components/{component}`.
- Excel: `app/Exports/Common/Sheets/Items.php` `collection()` → `Model::with('category')->withStock()->collectForExport(...)`, `map()` sets `stock_quantity`, `fields()` gains `track_stock`, `stock_quantity`. Import honours `track_stock` for free (fillable); `stock_quantity` and components are ignored on import (documented).
- Skill: `reference.md` items row (+ the five endpoints), searchable keys (+ `track_stock`), write rules (`stock_quantity` semantics, components one level, kit vs assembled), older-servers note; `SKILL.md` recipes "Check or adjust stock", "Define a kit", "Build units" after **Bills**; `scripts/la-update` items template adds `track_stock: (.track_stock | flag)` (never `stock_quantity` or `components`).
- Tests: `tests/Feature/Api/ResourcesTest.php` (fields; `stock_quantity` is a number on pgsql), `EndpointsTest.php` (movements list, adjust by `quantity` and `change`, 422 when both missing, build, component add/remove, 403 with a read-only user if one is easy to seed), `LazyLoadingTest.php` (create a tracked product with a movement and a kit with one component in each loop iteration; add `'api/items/' . $tracked . '/stock-movements'`).

## Verification

- After each PR: `docker run --rm -v "$PWD":/app -w /app libre-test-php:8.1 php -d memory_limit=-1 vendor/bin/phpunit` (full suite, ~6 min; 254 tests today) and the new files alone with `--filter`.
- PostgreSQL: a `postgres:15-alpine` container with `DB_CONNECTION=pgsql` and the same phpunit image built with `pdo_pgsql`, `--filter 'ItemStock|ItemComponents|Items|Api'`.
- Manual check in a disposable instance (see the Docker section of the README): enable tracking on a part with an opening count; create a kit with that part, sell it, confirm the part's history says "via <kit>"; enable tracking on the kit and Build 2 units, sell one; adjust via the modal; cancel an invoice. Then `scripts/la-api GET items/{id}` shows `stock_quantity` and `components`; `la-api -j '{"change":-2,"description":"breakage"}' POST items/{id}/stock-adjustments` and `la-api -j '{"quantity":3}' POST items/{id}/stock-builds` work.
- `php -l` each PHP file in the container before committing.

## Deliberately out of scope (noted for later)

"In stock: N" hint in the invoice item picker (needs a Vue rebuild: `AkauntingItemButton.vue` maps fields in three places and `ItemButton::render()` must add the aggregate); multi-level recipes; valuation/COGS; warehouses; low-stock alerts; blocking oversell; frozen per-sale recipes. Pre-existing bugs seen while reading, not fixed here: `Items@enable/disable` wipes an item's taxes (`UpdateItem` always deletes `taxes` and `tax_ids` is absent); `DeleteCompany` leaves `bank_statement_*` rows behind; `Items@autocomplete` has no permission check.
