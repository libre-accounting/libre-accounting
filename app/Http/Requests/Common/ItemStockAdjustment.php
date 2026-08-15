<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ItemStockAdjustment extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * Either `quantity` (set the stock on hand to this) or `change` (move it by this much).
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity'      => 'required_without:change|nullable|numeric',
            'change'        => 'required_without:quantity|nullable|numeric',
            'moved_at'      => 'nullable|date',
            'description'   => 'nullable|string',
        ];
    }
}
