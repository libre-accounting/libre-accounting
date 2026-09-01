<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'target_transaction_id' => [
                'required',
                'integer',
                Rule::exists('transactions', 'id')->where('company_id', company_id())->whereNull('deleted_at'),
            ],
        ];
    }
}
