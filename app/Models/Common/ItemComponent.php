<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * One part of a kit or an assembled item: `quantity` units of `component`
 * go into one unit of `item`. Recipes are one level deep.
 */
class ItemComponent extends Model
{
    use Cloneable, HasFactory;

    protected $table = 'item_components';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_id', 'component_id', 'quantity', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'double',
        'deleted_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item')->withDefault(['name' => trans('general.na')]);
    }

    public function component()
    {
        return $this->belongsTo('App\Models\Common\Item', 'component_id')->withDefault(['name' => trans('general.na')]);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\ItemComponent::new();
    }
}
