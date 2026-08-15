<?php

namespace Tests\Feature\Common;

use App\Models\Common\Item;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

/**
 * Helpers shared by the stock tests.
 *
 * Documents are built from factory requests and dispatched through
 * CreateDocument on purpose: Document::factory()->create() runs
 * UpdateDocument with a random item and fires status events of its own,
 * which makes stock assertions meaningless.
 */
abstract class StockTestCase extends FeatureTestCase
{
    protected function trackedItem(array $attributes = []): Item
    {
        return Item::factory()->enabled()->create(array_merge(['type' => 'product', 'track_stock' => true], $attributes));
    }

    protected function product(array $attributes = []): Item
    {
        return Item::factory()->enabled()->create(array_merge(['type' => 'product', 'track_stock' => false], $attributes));
    }

    protected function documentRequest(string $type, string $status, Item $item, float $quantity): array
    {
        $request = Document::factory()->{$type}()->{$status}()->items()->raw();

        $request['items'][0]['item_id'] = $item->id;
        $request['items'][0]['name'] = $item->name;
        $request['items'][0]['quantity'] = $quantity;

        return $request;
    }

    /**
     * Ledger rows, read without scopes so the assertions see what is stored.
     */
    protected function rows(?Document $document = null, ?Item $item = null)
    {
        $query = DB::table('item_stock_movements')->orderBy('id');

        if ($document) {
            $query->where('document_id', $document->id);
        }

        if ($item) {
            $query->where('item_id', $item->id);
        }

        return $query->get();
    }

    protected function onHand(Item $item): ?float
    {
        return $item->fresh()->stock_quantity;
    }

    protected function assertRefused(callable $action, string $message): void
    {
        try {
            $action();
        } catch (\Exception $e) {
            $this->assertSame($message, $e->getMessage());

            return;
        }

        $this->fail('Expected the action to be refused with: ' . $message);
    }
}
