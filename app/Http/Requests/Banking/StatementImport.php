<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class StatementImport extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|integer',
            'import'     => 'required|file|mimes:xml,txt|max:51200',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'import.mimes' => trans('statement_imports.errors.not_xml'),
        ];
    }
}
