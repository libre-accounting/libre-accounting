<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\ItemComponent as Request;
use App\Jobs\Common\CreateItemComponent;
use App\Models\Common\Item;

class ItemComponents extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // A recipe is part of the item, so editing it needs the update permission
        $this->middleware('permission:update-common-items')->only('create', 'store');
    }

    /**
     * Show the form for adding a part to the item's recipe.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function create(Item $item)
    {
        // Recipes are one level deep: no item inside itself, no kit inside a kit
        $candidates = Item::enabled()
            ->where('id', '<>', $item->id)
            ->whereDoesntHave('components')
            ->orderBy('name')
            ->pluck('name', 'id');

        $html = view('modals.items.component', compact('item', 'candidates'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('stock.add_component'),
                'buttons' => [
                    'cancel' => [
                        'text' => trans('general.cancel'),
                        'class' => 'btn-outline-secondary',
                    ],
                    'confirm' => [
                        'text' => trans('general.save'),
                        'class' => 'disabled:bg-green-100',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Add a part to the item's recipe, or change how much of it goes in.
     *
     * @param  Item  $item
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Item $item, Request $request)
    {
        $response = $this->ajaxDispatch(new CreateItemComponent($item, $request));

        if ($response['success']) {
            $response['redirect'] = url()->previous();

            $message = trans('messages.success.added', ['type' => trans('stock.components')]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
