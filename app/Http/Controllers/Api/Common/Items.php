<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Item as Request;
use App\Http\Requests\Common\ItemComponent as ComponentRequest;
use App\Http\Requests\Common\ItemStockAdjustment as AdjustmentRequest;
use App\Http\Requests\Common\ItemStockBuild as BuildRequest;
use App\Http\Resources\Common\Item as Resource;
use App\Http\Resources\Common\ItemComponent as ComponentResource;
use App\Http\Resources\Common\ItemStockMovement as MovementResource;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\CreateItemAssembly;
use App\Jobs\Common\CreateItemComponent;
use App\Jobs\Common\CreateItemStockAdjustment;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\DeleteItemComponent;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;
use App\Models\Common\ItemComponent;

class Items extends ApiController
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        // Methods outside the resource names get no permission check of their own
        $this->middleware('permission:read-common-items')->only('stockMovements');
        $this->middleware('permission:update-common-items')->only('adjustStock', 'buildStock', 'storeComponent', 'destroyComponent');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Item::with('category', 'media', 'taxes.tax', 'components.component')->withStock()->collect();

        return Resource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = Item::with('category', 'media', 'taxes.tax', 'components.component')->withStock()->find($id);

        if (! $item instanceof Item) {
            return $this->errorNotFound('No query results for model [' . Item::class . '] ' . $id);
        }

        return new Resource($item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $item = $this->dispatch(new CreateItem($request));

        return $this->created(route('api.items.show', $item->id), new Resource($item));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $item
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Item $item, Request $request)
    {
        $item = $this->dispatch(new UpdateItem($item, $request));

        return new Resource($item->fresh());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Item $item)
    {
        $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 1])));

        return new Resource($item->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Item $item)
    {
        $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 0])));

        return new Resource($item->fresh());
    }

    /**
     * The item's stock ledger, newest first.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function stockMovements(Item $item)
    {
        $movements = $item->stock_movements()
            ->with('via_item')
            ->orderByDesc('moved_at')
            ->orderByDesc('id')
            ->paginate(request('limit', 25));

        return MovementResource::collection($movements);
    }

    /**
     * Record a manual adjustment: `quantity` sets the stock on hand, `change` moves it.
     *
     * @param  Item  $item
     * @param  AdjustmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adjustStock(Item $item, AdjustmentRequest $request)
    {
        try {
            $movement = $this->dispatch(new CreateItemStockAdjustment($item, $request));

            return $this->created(route('api.items.stock-movements', $item->id), new MovementResource($movement));
        } catch (\Exception $e) {
            return $this->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Build units out of the item's components.
     *
     * @param  Item  $item
     * @param  BuildRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buildStock(Item $item, BuildRequest $request)
    {
        try {
            $movement = $this->dispatch(new CreateItemAssembly($item, $request));

            return $this->created(route('api.items.stock-movements', $item->id), new MovementResource($movement));
        } catch (\Exception $e) {
            return $this->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Add a part to the item's recipe, or change how much of it goes in.
     *
     * @param  Item  $item
     * @param  ComponentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeComponent(Item $item, ComponentRequest $request)
    {
        try {
            $component = $this->dispatch(new CreateItemComponent($item, $request));

            return $this->created(route('api.items.show', $item->id), new ComponentResource($component->load('component')));
        } catch (\Exception $e) {
            return $this->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Remove a part from the item's recipe.
     *
     * @param  Item  $item
     * @param  ItemComponent  $component
     * @return \Illuminate\Http\Response
     */
    public function destroyComponent(Item $item, ItemComponent $component)
    {
        if ($component->item_id != $item->id) {
            return $this->errorNotFound('No query results for model [' . ItemComponent::class . '] ' . $component->id);
        }

        $this->dispatch(new DeleteItemComponent($component));

        return $this->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            $this->dispatch(new DeleteItem($item));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
