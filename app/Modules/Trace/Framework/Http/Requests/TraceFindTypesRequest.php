<?php

namespace App\Modules\Trace\Framework\Http\Requests;

use App\Modules\Trace\Framework\Http\Services\RequestFilterRules;
use Illuminate\Foundation\Http\FormRequest;

class TraceFindTypesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            ...RequestFilterRules::services(),
            ...RequestFilterRules::text(),
            'logging_from'  => [
                'sometimes',
                'date',
            ],
            'logging_to'    => [
                'sometimes',
                'date',
            ],
            ...RequestFilterRules::data(),
            'has_profiling' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
