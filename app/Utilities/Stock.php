<?php

namespace App\Utilities;

use App\Models\Common\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Keeps the rows of item_stock_movements that are derived from documents in
 * step with those documents.
 *
 * Everything here goes through the query builder on purpose. It runs inside
 * model observers, where Eloquent would answer from the model cache, run the
 * retrieved hooks of DocumentItem for every line, and apply the Company scope
 * even though console and queue contexts have no current company.
 */
class Stock
{
    /**
     * Document statuses that leave stock alone.
     */
    public const NOT_COUNTED = ['draft', 'cancelled'];

    /**
     * Rewrite the movements derived from one document.
     *
     * Safe to call from any write path: it deletes what it derived before and
     * derives again from the stored document, lines, recipes and items.
     */
    public static function syncDocument(int $document_id): void
    {
        DB::transaction(function () use ($document_id) {
            DB::table('item_stock_movements')->where('document_id', $document_id)->delete();

            $document = DB::table('documents')->where('id', $document_id)->first();

            if (! $document || ! is_null($document->deleted_at)) {
                return;
            }

            if (! $direction = static::directionFor($document->type)) {
                return;
            }

            if (in_array($document->status, static::NOT_COUNTED)) {
                return;
            }

            $lines = DB::table('document_items')
                ->where('document_id', $document_id)
                ->whereNull('deleted_at')
                ->where('item_id', '>', 0)
                ->get(['id', 'item_id', 'quantity']);

            if ($lines->isEmpty()) {
                return;
            }

            $rows = static::rowsFor($document, $lines, $direction);

            if ($rows) {
                DB::table('item_stock_movements')->insert($rows);
            }
        });
    }

    /**
     * Rebuild every derived movement of an item, including the ones it gets
     * as a part of a kit. Used when tracking is switched or a recipe changes.
     */
    public static function resyncItem(Item $item): void
    {
        DB::transaction(function () use ($item) {
            DB::table('item_stock_movements')
                ->where('item_id', $item->id)
                ->whereNotNull('document_id')
                ->delete();

            $kit_ids = DB::table('item_components')
                ->where('component_id', $item->id)
                ->whereNull('deleted_at')
                ->pluck('item_id')
                ->all();

            $document_ids = DB::table('document_items')
                ->whereIn('item_id', array_merge([$item->id], $kit_ids))
                ->whereNull('deleted_at')
                ->distinct()
                ->pluck('document_id');

            foreach ($document_ids as $document_id) {
                static::syncDocument((int) $document_id);
            }
        });
    }

    /**
     * Units of an item on hand right now.
     */
    public static function onHand(int $item_id): float
    {
        return (float) DB::table('item_stock_movements')->where('item_id', $item_id)->sum('quantity');
    }

    /**
     * 1 when documents of this type bring stock in, -1 when they take it out,
     * null when they leave stock alone.
     */
    public static function directionFor(?string $type): ?int
    {
        // Recurring templates define the action too, but only the documents they create move stock
        if (empty($type) || Str::endsWith($type, '-recurring')) {
            return null;
        }

        return match (config('type.document.' . $type . '.inventory_stock_action')) {
            'increase' => 1,
            'decrease' => -1,
            default => null,
        };
    }

    protected static function rowsFor(object $document, $lines, int $direction): array
    {
        $line_item_ids = $lines->pluck('item_id')->unique()->all();

        $components = DB::table('item_components')
            ->whereIn('item_id', $line_item_ids)
            ->whereNull('deleted_at')
            ->get(['item_id', 'component_id', 'quantity'])
            ->groupBy('item_id');

        $candidate_ids = array_merge($line_item_ids, $components->flatten()->pluck('component_id')->all());

        $tracked = DB::table('items')
            ->where('company_id', $document->company_id)
            ->whereIn('id', array_values(array_unique($candidate_ids)))
            ->where('track_stock', true)
            ->pluck('id')
            ->flip();

        $now = now();
        $rows = [];

        foreach ($lines as $line) {
            foreach (static::expandLine($line, $tracked, $components) as [$item_id, $quantity, $via_item_id]) {
                $rows[] = [
                    'company_id' => $document->company_id,
                    'item_id' => $item_id,
                    'via_item_id' => $via_item_id,
                    'document_id' => $document->id,
                    'document_item_id' => $line->id,
                    'type' => $document->type,
                    'quantity' => $direction * $quantity,
                    'moved_at' => $document->issued_at,
                    'description' => null,
                    'created_from' => $document->created_from,
                    'created_by' => $document->created_by,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        return $rows;
    }

    /**
     * A tracked item moves itself. An untracked item with a recipe moves its
     * tracked parts instead. Anything else moves nothing.
     *
     * @return array  [item_id, quantity, via_item_id] triples
     */
    protected static function expandLine(object $line, $tracked, $components): array
    {
        if (isset($tracked[$line->item_id])) {
            return [[$line->item_id, (float) $line->quantity, null]];
        }

        $pairs = [];

        foreach ($components->get($line->item_id, collect()) as $component) {
            if (! isset($tracked[$component->component_id])) {
                continue;
            }

            $pairs[] = [$component->component_id, (float) $line->quantity * (float) $component->quantity, $line->item_id];
        }

        return $pairs;
    }
}
