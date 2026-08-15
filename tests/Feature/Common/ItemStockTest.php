<?php

namespace Tests\Feature\Common;

use App\Events\Document\DocumentMarkedSent;
use App\Events\Document\PaymentReceived;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\CreateItemStockAdjustment;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Jobs\Document\CancelDocument;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\RestoreDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Common\Company;
use App\Models\Common\Item;
use App\Models\Common\ItemStockMovement;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Utilities\Date;
use App\Utilities\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ItemStockTest extends StockTestCase
{
    public function testSentInvoiceDecreasesStock()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals(-3, $rows[0]->quantity);
        $this->assertEquals($item->id, $rows[0]->item_id);
        $this->assertNull($rows[0]->via_item_id);
        $this->assertEquals($invoice->items()->first()->id, $rows[0]->document_item_id);
        $this->assertEquals($this->company->id, $rows[0]->company_id);
        $this->assertEquals('invoice', $rows[0]->type);
        $this->assertEquals($invoice->issued_at->toDateTimeString(), Date::parse($rows[0]->moved_at)->toDateTimeString());
        $this->assertSame(-3.0, $this->onHand($item));
    }

    public function testReceivedBillIncreasesStock()
    {
        $item = $this->trackedItem();

        $bill = $this->dispatch(new CreateDocument($this->documentRequest('bill', 'received', $item, 5)));

        $rows = $this->rows($bill);

        $this->assertCount(1, $rows);
        $this->assertEquals(5, $rows[0]->quantity);
        $this->assertEquals('bill', $rows[0]->type);
        $this->assertSame(5.0, $this->onHand($item));
    }

    public function testDraftInvoiceMovesNothingUntilItIsSent()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'draft', $item, 3)));

        $this->assertCount(0, $this->rows($invoice));

        event(new DocumentMarkedSent($invoice));

        $this->assertEquals('sent', $invoice->fresh()->status);
        $this->assertCount(1, $this->rows($invoice));
        $this->assertSame(-3.0, $this->onHand($item));
    }

    public function testCancellingRemovesMovementsAndRestoringKeepsNone()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        $this->dispatch(new CancelDocument($invoice));

        $this->assertEquals('cancelled', $invoice->fresh()->status);
        $this->assertCount(0, $this->rows($invoice));

        $this->dispatch(new RestoreDocument($invoice));

        $this->assertEquals('draft', $invoice->fresh()->status);
        $this->assertCount(0, $this->rows($invoice));
    }

    public function testUntrackedItemIsIgnored()
    {
        $item = $this->product();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        $this->assertCount(0, $this->rows($invoice));
        $this->assertNull($this->onHand($item));
    }

    public function testChangingTheQuantityReplacesTheMovement()
    {
        $item = $this->trackedItem();
        $request = $this->documentRequest('invoice', 'sent', $item, 3);

        $invoice = $this->dispatch(new CreateDocument($request));
        $old_line_id = $invoice->items()->first()->id;

        $request['items'][0]['quantity'] = 7;

        $this->dispatch(new UpdateDocument($invoice, $request));

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals(-7, $rows[0]->quantity);
        $this->assertNotEquals($old_line_id, $rows[0]->document_item_id);
        $this->assertEquals($invoice->items()->first()->id, $rows[0]->document_item_id);
        $this->assertSame(-7.0, $this->onHand($item));
    }

    public function testChangingStatusAndLinesTogetherDerivesOnce()
    {
        $item = $this->trackedItem();
        $request = $this->documentRequest('invoice', 'draft', $item, 3);

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->assertCount(0, $this->rows($invoice));

        $request['status'] = 'sent';
        $request['items'][0]['quantity'] = 4;

        $this->dispatch(new UpdateDocument($invoice, $request));

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals(-4, $rows[0]->quantity);
    }

    public function testChangingTheIssueDateMovesTheMovement()
    {
        $item = $this->trackedItem();
        $request = $this->documentRequest('invoice', 'sent', $item, 3);

        $invoice = $this->dispatch(new CreateDocument($request));

        $request['issued_at'] = '2026-03-04 12:00:00';

        $this->dispatch(new UpdateDocument($invoice, $request));

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals('2026-03-04', Date::parse($rows[0]->moved_at)->toDateString());
    }

    public function testDeletingTheDocumentRemovesMovements()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        $this->dispatch(new DeleteDocument($invoice));

        $this->assertSoftDeleted('documents', ['id' => $invoice->id]);
        $this->assertCount(0, $this->rows($invoice));
        $this->assertSame(0.0, $this->onHand($item));
    }

    public function testDuplicateStartsAsDraftWithoutMovements()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        $clone = $this->dispatch(new DuplicateDocument($invoice));

        $this->assertEquals('draft', $clone->status);
        $this->assertCount(0, $this->rows($clone));
        $this->assertCount(1, $this->rows($invoice));
    }

    public function testRecurringTemplateMovesNothing()
    {
        $item = $this->trackedItem();

        $request = Document::factory()->invoice()->sent()->items()->recurring()->raw();
        $request['items'][0]['item_id'] = $item->id;
        $request['items'][0]['quantity'] = 3;

        $template = $this->dispatch(new CreateDocument($request));

        $this->assertEquals(Document::INVOICE_RECURRING_TYPE, $template->type);
        $this->assertCount(0, $this->rows($template));

        $this->assertNull(Stock::directionFor(Document::INVOICE_RECURRING_TYPE));
        $this->assertNull(Stock::directionFor(Document::BILL_RECURRING_TYPE));
        $this->assertSame(-1, Stock::directionFor(Document::INVOICE_TYPE));
        $this->assertSame(1, Stock::directionFor(Document::BILL_TYPE));
        $this->assertNull(Stock::directionFor('estimate'));
        $this->assertNull(Stock::directionFor(null));
    }

    public function testPaymentsDoNotChangeTheMovement()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        event(new PaymentReceived($invoice, [
            'paid_at' => $invoice->due_at,
            'type' => config('type.document.invoice.transaction_type'),
        ]));

        $this->assertEquals('paid', $invoice->fresh()->status);
        $this->assertCount(1, $this->rows($invoice));
        $this->assertSame(-3.0, $this->onHand($item));

        $this->dispatch(new DeleteTransaction($invoice->transactions()->first()));

        $this->assertEquals('sent', $invoice->fresh()->status);
        $this->assertCount(1, $this->rows($invoice));
        $this->assertSame(-3.0, $this->onHand($item));
    }

    public function testSwitchingTrackingOnBackfillsAndOffClears()
    {
        $item = $this->product();

        $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 2)));
        $this->dispatch(new CreateDocument($this->documentRequest('bill', 'received', $item, 5)));

        $this->assertCount(0, $this->rows(null, $item));

        $this->dispatch(new UpdateItem($item, ['track_stock' => 1]));

        $this->assertCount(2, $this->rows(null, $item));
        $this->assertSame(3.0, $this->onHand($item));

        $this->dispatch(new UpdateItem($item->fresh(), ['track_stock' => 0]));

        $this->assertCount(0, $this->rows(null, $item));
        $this->assertNull($this->onHand($item));
    }

    public function testOpeningCountAfterEarlierSales()
    {
        $item = $this->product();

        $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 2)));

        $this->dispatch(new UpdateItem($item, ['track_stock' => 1, 'stock_quantity' => 10]));

        $this->assertSame(10.0, $this->onHand($item));

        $rows = $this->rows(null, $item);

        $this->assertCount(2, $rows);
        $this->assertEquals(-2, $rows[0]->quantity);
        $this->assertNotNull($rows[0]->document_id);
        $this->assertEquals(12, $rows[1]->quantity);
        $this->assertNull($rows[1]->document_id);
        $this->assertEquals(ItemStockMovement::ADJUSTMENT, $rows[1]->type);
        $this->assertEquals(trans('stock.opening_count'), $rows[1]->description);
    }

    public function testCreatingATrackedItemWithAnOpeningCount()
    {
        $request = Item::factory()->enabled()->raw(['type' => 'product', 'track_stock' => 1, 'stock_quantity' => 4]);

        $item = $this->dispatch(new CreateItem($request));

        $this->assertTrue($item->track_stock);
        $this->assertSame(4.0, $this->onHand($item));
        $this->assertCount(1, $this->rows(null, $item));
    }

    public function testAdjustmentSetsOrChangesStock()
    {
        $item = $this->trackedItem();

        $this->dispatch(new CreateItemStockAdjustment($item, ['quantity' => 10]));

        $this->assertSame(10.0, $this->onHand($item));

        $movement = $this->dispatch(new CreateItemStockAdjustment($item, ['change' => -3, 'moved_at' => '2026-02-01', 'description' => 'Breakage']));

        $this->assertSame(7.0, $this->onHand($item));
        $this->assertEquals(-3, $movement->quantity);
        $this->assertEquals(ItemStockMovement::ADJUSTMENT, $movement->type);
        $this->assertNull($movement->document_id);
        $this->assertEquals('2026-02-01', $movement->moved_at->toDateString());
        $this->assertEquals('Breakage', $movement->description);

        $untracked = $this->product();

        $this->assertRefused(
            fn () => $this->dispatch(new CreateItemStockAdjustment($untracked, ['quantity' => 1])),
            trans('stock.errors.not_tracked', ['item' => $untracked->name])
        );
    }

    public function testAdjustmentSurvivesADocumentResync()
    {
        $item = $this->trackedItem();
        $adjustment = ItemStockMovement::factory()->forItem($item)->quantity(10)->create();
        $request = $this->documentRequest('invoice', 'sent', $item, 3);

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->assertSame(7.0, $this->onHand($item));

        $request['items'][0]['quantity'] = 5;

        $this->dispatch(new UpdateDocument($invoice, $request));

        $this->assertSame(5.0, $this->onHand($item));
        $this->assertDatabaseHas('item_stock_movements', ['id' => $adjustment->id, 'quantity' => 10]);
    }

    public function testSyncWorksWithoutACurrentCompany()
    {
        $item = $this->trackedItem();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $item, 3)));

        DB::table('item_stock_movements')->where('document_id', $invoice->id)->delete();

        Company::forgetCurrent();

        try {
            $this->assertNull(company_id());

            Stock::syncDocument($invoice->id);
        } finally {
            $this->company->makeCurrent();
        }

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals($this->company->id, $rows[0]->company_id);
    }

    public function testLinesSavedAfterTheDocumentAreCounted()
    {
        $item = $this->trackedItem();
        $request = $this->documentRequest('invoice', 'sent', $item, 2);

        // The Excel import saves the document first and its lines later, with no document save in between
        $document = Document::create(Arr::except($request, ['items']));

        $this->assertCount(0, $this->rows($document));

        foreach ([2, 5] as $quantity) {
            DocumentItem::create([
                'company_id' => $this->company->id,
                'type' => Document::INVOICE_TYPE,
                'document_id' => $document->id,
                'item_id' => $item->id,
                'name' => $item->name,
                'quantity' => $quantity,
                'price' => 10,
                'total' => 10 * $quantity,
            ]);
        }

        $rows = $this->rows($document);

        $this->assertCount(2, $rows);
        $this->assertSame(-7.0, $this->onHand($item));
    }

    public function testWithStockAggregateMatchesTheAccessor()
    {
        $low = $this->trackedItem();
        $high = $this->trackedItem();
        $untracked = $this->product();

        ItemStockMovement::factory()->forItem($low)->quantity(4)->create();
        ItemStockMovement::factory()->forItem($high)->quantity(9)->create();

        $items = Item::withStock()->orderBy('stock')->get();

        $this->assertSame(4.0, $items->firstWhere('id', $low->id)->stock_quantity);
        $this->assertSame(9.0, $items->firstWhere('id', $high->id)->stock_quantity);
        $this->assertNull($items->firstWhere('id', $untracked->id)->stock_quantity);
        $this->assertTrue($items->search(fn ($item) => $item->id == $low->id) < $items->search(fn ($item) => $item->id == $high->id));

        $this->assertSame(4.0, Item::find($low->id)->stock_quantity);
        $this->assertSame(0.0, Item::withStock()->find($this->trackedItem()->id)->stock_quantity);
    }

    public function testStockReadsDoNotLazyLoad()
    {
        $violations = [];

        Model::preventLazyLoading();
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) use (&$violations) {
            $violations[] = class_basename($model) . '->' . $relation;
        });

        $items = [$this->trackedItem(), $this->trackedItem(), $this->product()];

        foreach ($items as $item) {
            ItemStockMovement::factory()->forItem($item)->quantity(2)->create();
        }

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $items[0], 1)));
        Stock::syncDocument($invoice->id);
        Stock::resyncItem($items[0]);

        Item::withStock()->get()->each(fn ($item) => $item->stock_quantity);
        Item::all()->each(fn ($item) => $item->stock_quantity);

        $this->assertSame([], $violations);
    }

    public function testDeletingAnItemRemovesItsAdjustments()
    {
        $item = $this->trackedItem();

        ItemStockMovement::factory()->forItem($item)->quantity(10)->create();

        $this->dispatch(new DeleteItem($item));

        $this->assertSoftDeleted('items', ['id' => $item->id]);
        $this->assertCount(0, $this->rows(null, $item));
    }
}
