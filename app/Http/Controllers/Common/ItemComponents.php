<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Jobs\Common\DeleteItemComponent;
use App\Models\Common\ItemComponent;

class ItemComponents extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // A recipe is part of the item, so editing it needs the update permission
        $this->middleware('permission:update-common-items')->only('destroy');
    }

    /**
     * Remove a part from an item's recipe.
     *
     * @param  ItemComponent  $component
     *
     * @return Response
     */
    public function destroy(ItemComponent $component)
    {
        $response = $this->ajaxDispatch(new DeleteItemComponent($component));

        $response['redirect'] = route('items.edit', $component->item_id);

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans('stock.components')]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
