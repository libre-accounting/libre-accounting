<?php

namespace App\Models\Common;

use App\Traits\Owners;
use App\Traits\Sources;
use App\Traits\Tenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Laratrust\Contracts\Ownable;

/**
 * One row per stock movement of an item.
 *
 * Rows with a document_id are derived from an invoice or bill by
 * App\Utilities\Stock and are rewritten whenever that document changes.
 * Rows without one are manual: an adjustment or an assembly.
 *
 * Not based on App\Abstracts\Model on purpose: that base class soft-deletes
 * and caches, and a ledger wants neither.
 */
class ItemStockMovement extends Eloquent implements Ownable
{
    use HasFactory, Owners, Sources, Tenants;

    public const ADJUSTMENT = 'adjustment';

    public const ASSEMBLY = 'assembly';

    protected $table = 'item_stock_movements';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_id', 'via_item_id', 'document_id', 'document_item_id', 'type', 'quantity', 'moved_at', 'description', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'double',
        'moved_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item')->withDefault(['name' => trans('general.na')]);
    }

    public function via_item()
    {
        return $this->belongsTo('App\Models\Common\Item', 'via_item_id')->withDefault(['name' => trans('general.na')]);
    }

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document');
    }

    public function document_item()
    {
        return $this->belongsTo('App\Models\Document\DocumentItem');
    }

    public function scopeManual($query)
    {
        return $query->whereNull($this->qualifyColumn('document_id'));
    }

    public function scopeDerived($query)
    {
        return $query->whereNotNull($this->qualifyColumn('document_id'));
    }

    public function isManual(): bool
    {
        return is_null($this->document_id);
    }

    public function ownerKey($owner)
    {
        if ($this->isNotOwnable()) {
            return 0;
        }

        return $this->created_by;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\ItemStockMovement::new();
    }
}
