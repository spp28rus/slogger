<?php

namespace App\Modules\Trace\Framework\Http\Requests;

use App\Modules\Trace\Framework\Http\Services\RequestFilterRules;
use Illuminate\Foundation\Http\FormRequest;

class TraceFindStatusesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            ...RequestFilterRules::services(),
            ...RequestFilterRules::text(),
            ...RequestFilterRules::types(),
            ...RequestFilterRules::types(),
            ...RequestFilterRules::tags(),
            ...RequestFilterRules::loggedFromTo(),
            ...RequestFilterRules::data(),
            ...RequestFilterRules::hasProfiling(),
        ];
    }
}