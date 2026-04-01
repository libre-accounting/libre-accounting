<?php

namespace App\Http\Requests\Install;

use App\Abstracts\Http\FormRequest;

class Database extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'connection' => 'required|in:mysql,pgsql,sqlite',
            'hostname'   => 'required_unless:connection,sqlite',
            'username'   => 'required_unless:connection,sqlite',
            'database'   => 'required',
            'port'       => 'nullable|integer',
        ];
    }
}
