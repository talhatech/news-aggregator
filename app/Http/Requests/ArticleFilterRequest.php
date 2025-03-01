<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'search'        => ['sometimes', 'string', 'max:100'],
            'sources'       => ['sometimes', 'array'],
            'sources.*'     => ['string', 'max:30'],
            'categories'    => ['sometimes', 'array'],
            'categories.*'  => ['string', 'max:30'],
            'date_from'     => ['sometimes', 'date', 'date_format:Y-m-d H:i:s'],
            'date_to'       => ['sometimes', 'date', 'date_format:Y-m-d H:i:s', 'after_or_equal:date_from'],
        ];
    }
}
