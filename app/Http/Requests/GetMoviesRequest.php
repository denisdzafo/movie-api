<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetMoviesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'searchTerm' => 'nullable',
            'orderBy' => 'nullable',
            'orderProp' => 'nullable',
            'categories' => 'array|nullable'
        ];
    }
}
