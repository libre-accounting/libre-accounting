<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Exports\Common\Items as Export;
use App\Jobs\Common\DeleteItem;
use App\Models\Common\Item;

class Items extends BulkAction
{
    public $model = Item::class;

    public $text = 'general.items';

    public $path = [
        'group' => 'common',
        'type' => 'items',
    ];

    public $actions = [
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'path'          => ['group' => 'common', 'type' => 'items'],
            'type'          => '*',
            'permission'    => 'update-common-items',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'path'          => ['group' => 'common', 'type' => 'items'],
            'type'          => '*',
            'permission'    => 'update-common-items',
        ],
        'track'     => [
            'icon'          => 'inventory_2',
            'name'          => 'stock.track_stock',
            'message'       => 'stock.bulk.track',
            'permission'    => 'update-common-items',
        ],
        'untrack'   => [
            'icon'          => 'layers_clear',
            'name'          => 'stock.untrack_stock',
            'message'       => 'stock.bulk.untrack',
            'permission'    => 'update-common-items',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-common-items',
        ],
        'export'    => [
            'icon'          => 'file_download',
            'name'          => 'general.export',
            'message'       => 'bulk_actions.message.export',
            'type'          => 'download',
        ],
    ];

    public function __construct()
    {
        $this->messages += [
            'track'     => 'stock.messages.tracked',
            'untrack'   => 'stock.messages.untracked',
        ];
    }

    /**
     * Switch stock tracking on for the selected products. Services never
     * track stock, so each one selected is reported and left alone.
     */
    public function track($request)
    {
        foreach ($this->getSelectedRecords($request) as $item) {
            if ($item->type != 'product') {
                flash(trans('stock.errors.not_product', ['item' => $item->name]))->warning()->important();

                continue;
            }

            $this->setTracking($item, true);
        }
    }

    /**
     * Switch stock tracking off for the selected items.
     */
    public function untrack($request)
    {
        foreach ($this->getSelectedRecords($request) as $item) {
            $this->setTracking($item, false);
        }
    }

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request, 'taxes');

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeleteItem($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->exportExcel(new Export($selected), trans_choice('general.items', 2));
    }

    /**
     * The item observer rebuilds the ledger when the flag changes, so the
     * flag and the ledger land in one transaction.
     */
    protected function setTracking(Item $item, bool $track): void
    {
        \DB::transaction(function () use ($item, $track) {
            $item->track_stock = $track;
            $item->save();
        });
    }
}
