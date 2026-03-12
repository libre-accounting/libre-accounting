<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class LinkTransfer extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_transaction_id' => 'required|integer|exists:transactions,id',
        ];
    }
}
