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
            'source_ids'    => ['sometimes', 'array'],
            'source_ids.*'  => ['string', 'uuid'],
            'category_ids'  => ['sometimes', 'array'],
            'category_ids.*'=> ['string', 'uuid'],
            'date_from'     => ['sometimes', 'date', 'date_format:Y-m-d H:i:s'],
            'date_to'       => ['sometimes', 'date', 'date_format:Y-m-d H:i:s', 'after_or_equal:date_from'],
        ];
    }
}
