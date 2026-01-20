<?php

namespace App\Http\Requests\Wizard;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class Company extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $logo = 'nullable';

        if ($this->files->get('logo')) {
            $logo = 'mimes:' . config('filesystems.mimes')
                    . '|between:0,' . config('filesystems.max_size') * 1024
                    . '|dimensions:max_width=' . config('filesystems.max_width') . ',max_height=' . config('filesystems.max_height');
        }

        return [
            'logo' => $logo,
        ];
    }

    public function messages()
    {
        $logo_dimensions = trans('validation.custom.invalid_dimension', [
            'attribute'     => Str::lower(trans('settings.company.logo')),
            'width'         => config('filesystems.max_width'),
            'height'        => config('filesystems.max_height'),
        ]);

        return [
            'logo.dimensions' => $logo_dimensions,
        ];
    }
}
