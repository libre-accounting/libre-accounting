<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ItemComponent extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * The one-level rules (no self, no kit inside a kit) live in the job.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'component_id'  => 'required|integer|exists:items,id',
            'quantity'      => 'required|numeric|gt:0',
        ];
    }
}
