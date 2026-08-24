# Libre Accounting API reference

Everything is under `/api`. Auth is HTTP Basic (user email + password). The
company comes from `?company_id=`, then the `X-Company` header, then the
user's first enabled company. Bodies can be JSON, form-encoded or multipart.
`Accept` is ignored; responses are always JSON.

## Contents

- [Endpoints](#endpoints)
- [Lists: paging, sorting, search](#lists-paging-sorting-search)
- [Responses and errors](#responses-and-errors)
- [Write rules per resource](#write-rules-per-resource)
- [Older servers](#older-servers)

## Endpoints

`apiResource` means `GET /x`, `POST /x`, `GET /x/{id}`, `PATCH /x/{id}`,
`DELETE /x/{id}`. `enable`/`disable` are GETs that change data.

| Path | Notes |
|---|---|
| `ping` | `{"status":"ok","timestamp":...}` |
| `users` (+ `/{id}/enable`, `/disable`) | lists every user of the instance; show by id or email |
| `companies` (+ `/{id}/enable`, `/disable`, `/owner`) | only the caller's companies; `owner` is 200 empty or 401 |
| `dashboards` (+ enable/disable) | widgets carry computed `data`; `?start_date=&end_date=` |
| `items` (+ enable/disable) | products/services catalogue; `track_stock`, `stock_quantity` and `components` per item |
| `items/{id}/stock-movements` | GET, paged: the item's stock ledger, newest first |
| `items/{id}/stock-adjustments` | POST `quantity` (set to) or `change` (move by), optional `moved_at`, `description`; 400 when the item does not track stock |
| `items/{id}/stock-builds` | POST `quantity` (negative takes units apart): parts out, finished units in; 400 without tracking or components |
| `items/{id}/components` | POST `component_id`, `quantity` adds or changes a part; DELETE `/{component_id}` removes it; 400 for a kit inside a kit |
| `contacts` (+ enable/disable) | **needs `search=type:customer\|vendor`**; show by id or email |
| `documents` | **needs `search=type:invoice\|bill`**; show by id or document_number |
| `documents/{id}/transactions` | payments of a document: GET list (unpaginated), POST, GET/DELETE `/{tid}`; no update |
| `documents/{id}/received` | GET marks a draft bill received (`search=type:bill`); 400 for anything else |
| `accounts` (+ enable/disable) | show by id or number; `current_balance` included |
| `reconciliations` | |
| `transactions` | income and expense; transfers and splits are internal types |
| `transfers` | between two accounts; creates an expense-transfer + income-transfer pair |
| `reports` | show computes the report; `/create` and `/{id}/edit` exist but 500 |
| `categories` (+ enable/disable) | |
| `currencies` (+ enable/disable) | show by id or code |
| `taxes` (+ enable/disable) | |
| `settings` | list is all stored keys, unpaginated; show by id or key; update/delete by id |
| `translations/{locale}/all`, `translations/{locale}/{file}` | UI strings |

### Permissions

Each call needs `read-api` plus `<action>-<area>`: read for GET, create for
POST, update for PATCH and enable/disable, delete for DELETE.

| Area | Endpoints |
|---|---|
| `sales-customers` | contacts with `type:customer` |
| `purchases-vendors` | contacts with `type:vendor` |
| `sales-invoices` | documents with `type:invoice` |
| `purchases-bills` | documents with `type:bill` |
| `banking-transactions` | transactions, document payments |
| `banking-accounts`, `banking-transfers`, `banking-reconciliations` | same names |
| `common-items`, `common-companies`, `common-dashboards`, `common-reports` | same names; the stock and component endpoints of an item need read (GET) or update (POST, DELETE) on `common-items` |
| `settings-categories`, `settings-currencies`, `settings-taxes` | same names |
| `settings-company`, `settings-defaults` or `settings-localisation` (any of them) | settings |
| `auth-users` | users |

The built-in **accountant** role is read-only and lacks the settings
endpoints. **Admin** has everything.

## Lists: paging, sorting, search

- `limit` sets the page size (default 25, no maximum). `page` picks the page.
- `links.next` drops your query string, so re-add `search` and `limit`.
  `la-api -a` already does this.
- Sorting: `sort=<column>&direction=asc|desc`. Both are needed, and an
  unknown column falls back to the default order.
  - documents: document_number, contact_name, amount, status, issued_at, due_at (default `issued_at desc`)
  - transactions: type, number, paid_at, amount, category.name, account.name (default `paid_at desc`)
  - contacts: name, email, phone, enabled
  - items: name, category.name, sale_price, purchase_price, enabled, stock
  - accounts: name, number, balance, bank_name
  - categories: name, type, enabled; currencies: name, code, rate; taxes: name, rate
- Unless the search has a `type:` filter, documents and transactions hide
  the `*-recurring` templates. Transactions also hide split parts.

### Search syntax (`search=`)

- `key:value` or `key=value`; also `>`, `>=`, `<`, `<=`.
- Lists: `status:sent,viewed,partial`.
- Spaces mean AND; use an explicit `or`. Parentheses group; `not` negates.
  There is no `!=`.
- Quote values that contain spaces: `name:"Acme Ltd"`.
- A bare word or quoted string is a free-text `LIKE` across the columns
  marked (s) below: `"hosting"`.
- Booleans: `enabled`, `not enabled`, `enabled:1`.
- Dates: `paid_at:2026` (year), `paid_at:2026-03` (month),
  `paid_at:2026-03-15` (day), or ranges `paid_at>=2026-01-01 paid_at<2026-02-01`.
- Relations: `account.name:"Main Bank"`.
- `sort:-amount` inside `search` also works.

**An unknown key or a syntax error drops the whole search silently** and
the endpoint returns unfiltered data. Always check the rows on the client
side.

Searchable keys (s = also free text):

- **documents**: id, type(s), document_number(s), order_number(s), status,
  `invoiced_at`/`billed_at` (the issue date), due_at, amount, currency_code,
  contact_id, contact_name(s), contact_email(s), contact_tax_number,
  contact_phone(s), contact_address(s), category_id, parent_id
- **transactions**: id, type, account_id, paid_at, amount, currency_code,
  document_id, contact_id, description(s), payment_method, reference,
  category_id, parent_id. There is no `number`.
- **contacts**: id, type, name(s), email(s), tax_number(s), phone(s),
  address(s), website(s), currency_code, reference, user_id, enabled
- **items**: id, name(s), description(s), enabled, track_stock, category_id,
  sale_price, purchase_price
- **accounts**: id, name(s), number(s), bank_name(s), bank_address(s),
  currency_code, enabled
- **categories**: id, name(s), enabled, type (income|expense|item|other)
- **currencies**: id, name(s), code(s), rate, enabled
- **taxes**: id, name(s), type, rate, enabled
- **reconciliations**: id, account, closing_balance, reconciled, started_at, ended_at
- **users**: id, name(s), email(s), enabled, last_logged_in_at
- All of them also have `created_at` and `updated_at`.

## Responses and errors

- A single record is `{"data": {...}}`. **Exception:** `GET reports/{id}`
  is unwrapped, with the computed report in `.data`.
- A list is `{"data": [...], "links": {...}, "meta": {"current_page", "last_page", "per_page", "total", ...}}`.
- Nested collections are wrapped too: `items.data`, `totals.data`,
  `transactions.data`, `taxes.data`.
- Dates come back as ISO 8601 in the company timezone. Money comes back as a
  raw number plus a `*_formatted` string.
- Create returns 201 with the record. Update and enable/disable return 200
  with the record. Delete returns 204 with no body.
- An error is `{"message", "errors"?, "status_code"}`.
  - 422 is a validation failure, with `errors: {field: [messages]}`.
  - A missing id is a 404 "No query results for model ..." (a 500 on older
    servers).
  - Business-rule failures are 401 on some endpoints and 500 on others.
- The rate limit is 60 requests per minute, shared across the whole instance.
  Exceeding it returns 429 with `Retry-After`.

## Write rules per resource

Shared rules:

- `company_id`, `created_by` and `created_from` are filled by the server.
- Updates need the full field set; use `la-update`. Prefer PATCH, because
  older servers reject PUT with "already been taken" errors.
- Amounts are plain decimals with a `.` and no separators or symbols.
- Dates are `Y-m-d H:i:s`. A date with time `00:00:00` gets the current time
  of day added.
- `enabled` is `1` or `0`.
- Files go up as multipart. When updating a record that has files, list the
  files to keep: `uploaded_attachment: [{"id": <media id>}]`. `la-update`
  does this for you.

**transactions**

- Required: `type` (`income`|`expense`), `number` (unique; see
  `la-next-number transaction`), `account_id`, `paid_at`, `amount` (>= 0),
  `currency_code`, `currency_rate`, `category_id`, `payment_method`.
- Optional: `contact_id`, `description`, `reference`, `attachment[]`
  (files).
- Recurring templates use `recurring_frequency` (`daily|weekly|monthly|yearly|custom`)
  plus `recurring_started_at`, `recurring_limit` (`count|date`),
  `recurring_limit_count` or `recurring_limit_date`, and `recurring_interval`
  with `recurring_custom_frequency` when custom.
- Setting `document_id` here does not update the invoice. Pay documents
  through `documents/{id}/transactions` instead.
- Reconciled transactions and transfer halves can't be updated or deleted.

**document payments** (`POST documents/{id}/transactions`)

- Same fields as transactions. `type` is `income` for invoices and `expense`
  for bills.
- The document's status becomes `partial` or `paid`; deleting the payment
  reverts it.
- Over-payment fails with a 500. There is no update; delete and re-create
  instead.

**documents** (`?search=type:invoice|bill` on every call)

- Required: `type`, `document_number` (unique per type; see
  `la-next-number`), `status`, `issued_at`, `due_at` (on or after
  `issued_at`), `amount` (send `0`), `currency_code`, `currency_rate`,
  `contact_id`, `contact_name`, `category_id`, and `items[]`.
- Each item is `{item_id?, name, description, quantity, price (> 0), tax_ids?: [..], discount?, discount_type?}`.
  - `discount_type: "percentage"` makes the discount a percentage; anything
    else is a fixed amount.
  - Leaving out `item_id` creates a new catalogue item.
- Statuses: invoices use `draft|sent|viewed|partial|paid|cancelled`; bills
  use `draft|received|viewed|partial|paid|cancelled`. When payments exist,
  the server forces `partial` or `paid`.
- Optional: `contact_email`, `contact_tax_number`, `contact_phone`,
  `contact_address`, `contact_city`, `contact_zip_code`, `contact_state`,
  `contact_country`, `order_number`, `title`, `subheading`, `notes`,
  `footer`, `attachment[]`.
  - Whole-document discount: `discount` + `discount_type`.
  - Extra totals: `totals[]` as `{name, amount, code?, operator: addition|subtraction}`.
- An update rebuilds items, item taxes and totals from what you send.
- Delete fails if any payment is reconciled; otherwise it also deletes the
  payments.

**contacts** (`?search=type:customer|vendor` on every call)

- Required: `type`, `name`, `currency_code`.
- `email` is optional, but if sent it must be unique per type and its
  domain must have a working mail (MX) record.
- Optional: `tax_number`, `phone`, `address`, `city`, `zip_code`, `state`,
  `country` (ISO code), `website`, `reference`, `enabled`, `logo` (image).
- `create_user: "true"` creates a client-portal user and emails an
  invitation. Only send it when asked.
- Delete is blocked while the contact has documents or transactions.

**items**

- Required: `type` (`product`|`service`) and `name`.
- Optional: `description`, `sale_price`, `purchase_price`, `category_id`
  (an `item` category), `tax_ids[]`, `enabled`, `picture`.
- An update replaces the taxes only when it sends `tax_ids`; `tax_ids: []`
  clears them, and a request without the key, like enable/disable, keeps
  them. A server older than 2026-08-24 replaced them on every update and
  wiped them on enable/disable, so send `tax_ids` from the GET there (the
  `la-update` template already does).
- Delete is blocked while the item is used on documents or as a component.
- Stock: `track_stock` (bool) switches tracking on or off; switching it on
  rebuilds the ledger from past invoices and bills. `stock_quantity` in the
  response is the units on hand (null when untracked). In a request it is
  honoured only on create, or when `track_stock` flips to true: it then
  becomes the opening count. On an already-tracked item it is ignored, so a
  GET-then-PATCH round trip never moves stock; use `stock-adjustments`.
- Movements: sent/viewed/partial/paid invoices take stock out, received or
  paid bills bring it in, drafts and cancelled documents move nothing.
  Rows with a `document_id` are derived and rewritten whenever the document
  changes; `adjustment` and `assembly` rows are manual and permanent (undo
  one with a reverse adjustment or a negative build).
- Components: `components.data[]` lists the recipe (`component_id`, `name`,
  `quantity` per unit), one level deep. An untracked item with components is
  a kit: selling it takes the parts out of stock. A tracked item with
  components is assembled ahead with `stock-builds`; selling it takes the
  finished units. Changing a recipe recalculates every document that sold
  the kit.

**categories**

- Required: `name`, `type` (`income|expense|item|other`), and `color` (hex,
  e.g. `#3b82f6`).
- Optional: `parent_id`, `enabled`.
- You can't change the type of, disable, or delete a category in use, nor
  the default income/expense category.

**accounts**

- Required: `type` (`bank`|`credit_card`), `name`, `number`,
  `currency_code`.
- `opening_balance` is required for credit cards and optional (>= 0) for
  bank accounts.
- Optional: `bank_name`, `bank_phone`, `bank_address`, `default_account`.
- On older servers, enabling or disabling an account that has transactions
  needs `?currency_code=<its code>`, and the same for a currency in use with
  `?code=<its code>`.

**transfers**

- Required: `from_account_id`, `to_account_id`, `amount` (> 0),
  `transferred_at` (**`Y-m-d` only**), `payment_method`.
- Optional: `description`, `reference`, and for cross-currency transfers the
  `from_account_rate` and `to_account_rate`.

**taxes**

- Required: `name`, `rate` (0-100), `type`
  (`normal|inclusive|compound|fixed|withholding`). Only one compound tax is
  allowed.
- Note that GET does not return `type`.

**currencies**

- Required: `name`, `code`, `rate` (> 0).
- Optional: `precision`, `symbol`, `symbol_first`, `decimal_mark`,
  `thousands_separator`, `default_currency`, `enabled`.

**reconciliations**

- Required: `account_id`, `started_at`, `ended_at`, `closing_balance`.
- `transactions` is a map: `{"income_12": true, "expense_15": true}`.
  `reconcile: 1` locks the listed transactions.

**settings**

- POST `{key, value}` creates a key; a duplicate key is a 500.
- Update and delete by numeric id.
- Payment methods are in the `offline-payments.methods` setting.
- Numbering settings are `<invoice|bill|transaction>.number_prefix`,
  `.number_digit` and `.number_next`. A key that has never been saved
  doesn't exist; its default applies.

**users**

- Creating a user always emails an invitation.
- `roles` is a role id sent **as a string**; `companies` is an array of
  company ids.
- Don't create, disable or delete users unless asked.

## Older servers

Servers released before these API fixes behave differently. If
`GET transactions/{id}` has no `number` field, assume all of the following:

| Symptom | Workaround |
|---|---|
| `GET documents/{id}/received` returns 500 | `la-update bills ID '{"status":"received"}'` |
| `GET items/{id}` has no `track_stock` | the server predates stock tracking; the stock and component endpoints 404 |
| PUT reports "number/email has already been taken" | use PATCH |
| PATCH without `enabled` disables the record, or fails if it's in use | send `enabled: 1` (`la-update` does) |
| Missing id returns 500 instead of 404 | treat "No query results" as not found |
| Transaction GET has no `number` | `la-update transactions` refuses; edit in the web UI |
| Reports return `data: []`, or 500 with filters | compute figures from transactions |
| Document items lack `quantity`, contacts lack city/zip/state/country | `la-update` on those would blank them; edit in the web UI |
| Invoice item without a `description` key returns 500 | always send `description` |
