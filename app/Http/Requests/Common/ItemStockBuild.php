<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ItemStockBuild extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * A negative quantity takes units apart again.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity'      => 'required|numeric|not_in:0',
            'moved_at'      => 'nullable|date',
            'description'   => 'nullable|string',
        ];
    }
}
