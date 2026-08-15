<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\ItemStockBuild as BuildRequest;
use App\Jobs\Common\CreateItemAssembly;
use App\Jobs\Common\CreateItemStockAdjustment;
use App\Models\Common\Item;
use App\Utilities\Stock;
use Illuminate\Http\Request;

class ItemStock extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Adjusting or building stock changes the item, so every action needs the update permission
        $this->middleware('permission:update-common-items')->only('create', 'store', 'createBuild', 'storeBuild');
    }

    /**
     * Show the form for a manual adjustment.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function create(Item $item)
    {
        $html = view('modals.items.stock', compact('item'))->render();

        return $this->modal($html, trans('stock.adjust'));
    }

    /**
     * Record a manual adjustment.
     *
     * The form posts one amount and a mode; the job takes `quantity` (set to) or `change` (move by).
     *
     * @param  Item  $item
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Item $item, Request $request)
    {
        $request->validate([
            'mode' => 'required|in:set,change',
            'amount' => 'required|numeric',
            'moved_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $response = $this->ajaxDispatch(new CreateItemStockAdjustment($item, [
            ($request->get('mode') == 'change') ? 'change' : 'quantity' => $request->get('amount'),
            'moved_at' => $request->get('moved_at'),
            'description' => $request->get('description'),
        ]));

        if ($response['success']) {
            $response['redirect'] = url()->previous();

            $message = trans('stock.messages.adjusted', ['item' => $item->name, 'quantity' => Stock::onHand($item->id) + 0]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }

    /**
     * Show the form for building units out of components.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function createBuild(Item $item)
    {
        $components = $item->components()->with('component')->get();

        $html = view('modals.items.build', compact('item', 'components'))->render();

        return $this->modal($html, trans('stock.build'));
    }

    /**
     * Build units out of components.
     *
     * @param  Item  $item
     * @param  BuildRequest  $request
     *
     * @return Response
     */
    public function storeBuild(Item $item, BuildRequest $request)
    {
        $response = $this->ajaxDispatch(new CreateItemAssembly($item, $request));

        if ($response['success']) {
            $response['redirect'] = url()->previous();

            $message = trans('stock.messages.built', ['item' => $item->name, 'quantity' => $request->get('quantity') + 0]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }

    protected function modal(string $html, string $title)
    {
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => $title,
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
}
