<?php

namespace Tests\Feature\Common;

use App\Exports\Common\Items as Export;
use App\Jobs\Common\CreateItem;
use App\Models\Common\Item;
use App\Models\Common\ItemComponent;
use App\Models\Common\ItemStockMovement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\FeatureTestCase;

class ItemsTest extends FeatureTestCase
{
	public function testItShouldSeeItemListPage()
	{
		$this->loginAs()
			->get(route('items.index'))
			->assertStatus(200)
			->assertSeeText(trans_choice('general.items', 2));
	}

	public function testItShouldSeeItemCreatePage()
	{
		$this->loginAs()
			->get(route('items.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.items', 1)]));
	}

	public function testItShouldCreateItem()
	{
		$request = $this->getRequest();

		$this->loginAs()
			->post(route('items.store'), $request)
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$this->assertDatabaseHas('items', $request);
	}

	public function testItShouldSeeItemUpdatePage()
	{
		$request = $this->getRequest();

        $item = $this->dispatch(new CreateItem($request));

		$this->loginAs()
			->get(route('items.edit', $item->id))
			->assertStatus(200)
			->assertSee($item->name);
	}

	public function testItShouldUpdateItem()
	{
		$request = $this->getRequest();

		$item = $this->dispatch(new CreateItem($request));

		$request['name'] = $this->faker->text(15);

		$this->loginAs()
			->patch(route('items.update', $item->id), $request)
			->assertStatus(200)
			->assertSee($request['name']);

		$this->assertFlashLevel('success');

		$this->assertDatabaseHas('items', $request);
	}

	public function testItShouldDeleteItem()
	{
		$request = $this->getRequest();

		$item = $this->dispatch(new CreateItem($request));

		$this->loginAs()
			->delete(route('items.destroy', $item->id))
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$this->assertSoftDeleted('items', $request);
	}

    public function testItShouldExportItems()
    {
        $count = 5;
        Item::factory()->count($count)->create();

        Excel::fake();

        $this->loginAs()
            ->get(route('items.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.items', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->sheets()[0]->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedItems()
    {
        $create_count = 5;
        $select_count = 3;

        $items = Item::factory()->count($create_count)->create();

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'common', 'type' => 'items']),
                ['handle' => 'export', 'selected' => $items->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.items', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                // Assert that the correct export is downloaded.
                return $export->sheets()[0]->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportItems()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('items.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'items.xlsx',
                        File::get(public_path('files/import/items.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('items.xlsx');

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeStockOnItemList()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        ItemStockMovement::factory()->forItem($item)->quantity(7)->create();

        $this->loginAs()
            ->get(route('items.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('stock.stock'))
            ->assertSee('index-line-actions-adjust-stock-item-' . $item->id);
    }

    public function testItShouldEnableTrackingWithAnOpeningCount()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => false]);

        $request = $this->getRequest() + ['track_stock' => 1, 'stock_quantity' => 10];

        $this->loginAs()
            ->patch(route('items.update', $item->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSame(10.0, $item->fresh()->stock_quantity);
        $this->assertDatabaseHas('item_stock_movements', ['item_id' => $item->id, 'type' => ItemStockMovement::ADJUSTMENT]);
    }

    public function testItShouldSeeStockSectionOnEditPage()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        ItemStockMovement::factory()->forItem($item)->quantity(3)->create(['description' => 'Opening shelf count']);

        $this->loginAs()
            ->get(route('items.edit', $item->id))
            ->assertStatus(200)
            ->assertSeeText(trans('stock.movements'))
            ->assertSeeText('Opening shelf count')
            ->assertSee('button-adjust-stock')
            ->assertSee('button-add-component');
    }

    public function testItShouldSeeComponentsOnEditPage()
    {
        $composite = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true, 'name' => 'Charger bundle']);
        $part = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true, 'name' => 'Charger unit']);

        ItemComponent::factory()->forItem($composite)->component($part)->quantity(2)->create();

        $this->loginAs()
            ->get(route('items.edit', $composite->id))
            ->assertStatus(200)
            ->assertSeeText(trans('stock.components'))
            ->assertSeeText('Charger unit')
            ->assertSee('button-build-stock')
            ->assertSee('button-add-component');

        // A part cannot have a recipe of its own, so its page says so instead of offering one
        $this->loginAs()
            ->get(route('items.edit', $part->id))
            ->assertStatus(200)
            ->assertSeeText(trans('stock.errors.kit_as_component', ['item' => 'Charger unit']))
            ->assertDontSee('button-add-component');

        $this->loginAs()
            ->get(route('modals.items.stock.build.create', $composite->id))
            ->assertStatus(200)
            ->assertJsonPath('data.title', trans('stock.build'))
            ->assertSee('Charger unit');
    }

    public function testItShouldAdjustStockFromTheModal()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        $this->loginAs()
            ->get(route('modals.items.stock.create', $item->id))
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', trans('stock.adjust'));

        $this->loginAs()
            ->post(route('modals.items.stock.store', $item->id), ['mode' => 'set', 'amount' => 12, 'moved_at' => now()->toDateString(), 'description' => 'Count'])
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSame(12.0, $item->fresh()->stock_quantity);

        $this->loginAs()
            ->post(route('modals.items.stock.store', $item->id), ['mode' => 'change', 'amount' => -2])
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSame(10.0, $item->fresh()->stock_quantity);
        $this->assertFlashLevel('success');
    }

    public function testItShouldRefuseAdjustingAnUntrackedItem()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => false]);

        $this->loginAs()
            ->post(route('modals.items.stock.store', $item->id), ['mode' => 'set', 'amount' => 5])
            ->assertStatus(200)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', trans('stock.errors.not_tracked', ['item' => $item->name]));

        $this->assertDatabaseMissing('item_stock_movements', ['item_id' => $item->id]);
    }

    public function testItShouldBuildStockFromTheModal()
    {
        $composite = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);
        $part = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        ItemComponent::factory()->forItem($composite)->component($part)->quantity(2)->create();

        $this->loginAs()
            ->get(route('modals.items.stock.build.create', $composite->id))
            ->assertStatus(200)
            ->assertJsonPath('data.title', trans('stock.build'));

        $this->loginAs()
            ->post(route('modals.items.stock.build.store', $composite->id), ['quantity' => 3])
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSame(3.0, $composite->fresh()->stock_quantity);
        $this->assertSame(-6.0, $part->fresh()->stock_quantity);
    }

    public function testItShouldManageComponentsFromTheModal()
    {
        $kit = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => false]);
        $part = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        $this->loginAs()
            ->get(route('modals.items.components.create', $kit->id))
            ->assertStatus(200)
            ->assertJsonPath('data.title', trans('stock.add_component'));

        $this->loginAs()
            ->post(route('modals.items.components.store', $kit->id), ['component_id' => $part->id, 'quantity' => 2])
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $component = ItemComponent::where('item_id', $kit->id)->where('component_id', $part->id)->first();

        $this->assertNotNull($component);
        $this->assertEquals(2, $component->quantity);

        $this->loginAs()
            ->delete(route('items.components.destroy', $component->id))
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect', route('items.edit', $kit->id));

        $this->assertSoftDeleted('item_components', ['id' => $component->id]);
    }

    public function getRequest()
    {
        return Item::factory()->enabled()->raw();
    }
}
