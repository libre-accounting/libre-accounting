<?php

namespace Tests\Feature\Common;

use App\Jobs\Common\CreateItemAssembly;
use App\Jobs\Common\CreateItemComponent;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\DeleteItemComponent;
use App\Jobs\Common\UpdateItem;
use App\Jobs\Document\CreateDocument;
use App\Models\Common\ItemComponent;
use App\Models\Common\ItemStockMovement;

class ItemComponentsTest extends StockTestCase
{
    public function testSellingAKitTakesItsTrackedPartsOutOfStock()
    {
        $kit = $this->product();
        $tracked = $this->trackedItem();
        $untracked = $this->product();

        ItemComponent::factory()->forItem($kit)->component($tracked)->quantity(2)->create();
        ItemComponent::factory()->forItem($kit)->component($untracked)->quantity(1)->create();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $kit, 3)));

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals($tracked->id, $rows[0]->item_id);
        $this->assertEquals(-6, $rows[0]->quantity);
        $this->assertEquals($kit->id, $rows[0]->via_item_id);
        $this->assertEquals($invoice->items()->first()->id, $rows[0]->document_item_id);

        $this->assertNull($this->onHand($kit));
        $this->assertSame(-6.0, $this->onHand($tracked));
        $this->assertNull($this->onHand($untracked));
    }

    public function testATrackedCompositeMovesItselfNotItsParts()
    {
        $composite = $this->trackedItem();
        $part = $this->trackedItem();

        ItemComponent::factory()->forItem($composite)->component($part)->quantity(2)->create();

        $invoice = $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $composite, 3)));

        $rows = $this->rows($invoice);

        $this->assertCount(1, $rows);
        $this->assertEquals($composite->id, $rows[0]->item_id);
        $this->assertEquals(-3, $rows[0]->quantity);
        $this->assertNull($rows[0]->via_item_id);
        $this->assertSame(0.0, $this->onHand($part));
    }

    public function testBuildingUnitsMovesPartsIntoFinishedUnits()
    {
        $composite = $this->trackedItem();
        $tracked = $this->trackedItem();
        $untracked = $this->product();

        ItemComponent::factory()->forItem($composite)->component($tracked)->quantity(2)->create();
        ItemComponent::factory()->forItem($composite)->component($untracked)->quantity(1)->create();

        $movement = $this->dispatch(new CreateItemAssembly($composite, ['quantity' => 5, 'description' => 'Batch 1']));

        $this->assertSame(5.0, $this->onHand($composite));
        $this->assertSame(-10.0, $this->onHand($tracked));
        $this->assertNull($this->onHand($untracked));

        $this->assertEquals(ItemStockMovement::ASSEMBLY, $movement->type);
        $this->assertNull($movement->via_item_id);
        $this->assertNull($movement->document_id);

        $part_rows = $this->rows(null, $tracked);

        $this->assertCount(1, $part_rows);
        $this->assertEquals(ItemStockMovement::ASSEMBLY, $part_rows[0]->type);
        $this->assertEquals($composite->id, $part_rows[0]->via_item_id);
        $this->assertEquals('Batch 1', $part_rows[0]->description);

        // Taking units apart again
        $this->dispatch(new CreateItemAssembly($composite, ['quantity' => -2]));

        $this->assertSame(3.0, $this->onHand($composite));
        $this->assertSame(-6.0, $this->onHand($tracked));
    }

    public function testBuildingNeedsTrackingAndARecipe()
    {
        $plain = $this->trackedItem();

        $this->assertRefused(
            fn () => $this->dispatch(new CreateItemAssembly($plain, ['quantity' => 1])),
            trans('stock.errors.no_components', ['item' => $plain->name])
        );

        $kit = $this->product();
        ItemComponent::factory()->forItem($kit)->component($this->trackedItem())->create();

        $this->assertRefused(
            fn () => $this->dispatch(new CreateItemAssembly($kit, ['quantity' => 1])),
            trans('stock.errors.not_tracked', ['item' => $kit->name])
        );
    }

    public function testChangingTheRecipeResyncsPastSales()
    {
        $kit = $this->product();
        $part = $this->trackedItem();

        $component = ItemComponent::factory()->forItem($kit)->component($part)->quantity(2)->create();

        $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $kit, 3)));

        $this->assertSame(-6.0, $this->onHand($part));

        $updated = $this->dispatch(new CreateItemComponent($kit, ['component_id' => $part->id, 'quantity' => 3]));

        $this->assertEquals($component->id, $updated->id);
        $this->assertSame(-9.0, $this->onHand($part));

        $this->dispatch(new DeleteItemComponent($updated));

        $this->assertSame(0.0, $this->onHand($part));
        $this->assertCount(0, $this->rows(null, $part));
    }

    public function testAddingAPartToARecipeStartsCountingIt()
    {
        $kit = $this->product();
        $part = $this->trackedItem();

        $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $kit, 2)));

        $this->assertCount(0, $this->rows(null, $part));

        $this->dispatch(new CreateItemComponent($kit, ['component_id' => $part->id, 'quantity' => 4]));

        $rows = $this->rows(null, $part);

        $this->assertCount(1, $rows);
        $this->assertEquals(-8, $rows[0]->quantity);
        $this->assertEquals($kit->id, $rows[0]->via_item_id);
    }

    public function testSwitchingTrackingOnAPartBackfillsKitSales()
    {
        $kit = $this->product();
        $part = $this->product();

        ItemComponent::factory()->forItem($kit)->component($part)->quantity(1)->create();

        $this->dispatch(new CreateDocument($this->documentRequest('invoice', 'sent', $kit, 3)));

        $this->assertCount(0, $this->rows(null, $part));

        $this->dispatch(new UpdateItem($part, ['track_stock' => 1]));

        $rows = $this->rows(null, $part);

        $this->assertCount(1, $rows);
        $this->assertEquals(-3, $rows[0]->quantity);
        $this->assertEquals($kit->id, $rows[0]->via_item_id);
    }

    public function testRecipesAreOneLevelDeep()
    {
        $kit = $this->product();
        $part = $this->product();
        $other = $this->product();

        ItemComponent::factory()->forItem($kit)->component($part)->create();

        $this->assertRefused(
            fn () => $this->dispatch(new CreateItemComponent($kit, ['component_id' => $kit->id, 'quantity' => 1])),
            trans('stock.errors.component_is_self')
        );

        $this->assertRefused(
            fn () => $this->dispatch(new CreateItemComponent($other, ['component_id' => $kit->id, 'quantity' => 1])),
            trans('stock.errors.component_is_kit', ['item' => $kit->name])
        );

        $this->assertRefused(
            fn () => $this->dispatch(new CreateItemComponent($part, ['component_id' => $other->id, 'quantity' => 1])),
            trans('stock.errors.kit_as_component', ['item' => $part->name])
        );
    }

    public function testAnItemUsedAsAPartCannotBeDeleted()
    {
        $kit = $this->product();
        $part = $this->product();

        ItemComponent::factory()->forItem($kit)->component($part)->create();

        $this->expectException(\Exception::class);

        $this->dispatch(new DeleteItem($part));
    }

    public function testDuplicatingAnItemCopiesItsRecipe()
    {
        $kit = $this->product();

        ItemComponent::factory()->forItem($kit)->component($this->product())->quantity(2)->create();
        ItemComponent::factory()->forItem($kit)->component($this->product())->quantity(1)->create();

        $clone = $kit->duplicate();

        $this->assertNotEquals($kit->id, $clone->id);
        $this->assertCount(2, $clone->components()->get());
        $this->assertCount(2, $kit->components()->get());
    }
}
