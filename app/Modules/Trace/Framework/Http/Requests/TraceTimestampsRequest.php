<?php

namespace App\Modules\Trace\Framework\Http\Requests;

use App\Modules\Trace\Enums\TraceTimestampEnum;
use App\Modules\Trace\Enums\TraceTimestampPeriodEnum;
use App\Modules\Trace\Framework\Http\Services\RequestFilterRules;
use Illuminate\Foundation\Http\FormRequest;

class TraceTimestampsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'timestamp_period' => [
                'required',
                'string',
                'in:' . implode(
                    ',',
                    array_map(
                        fn(TraceTimestampPeriodEnum $enum) => $enum->value,
                        TraceTimestampPeriodEnum::cases()
                    )
                ),
            ],
            'timestamp_step'   => [
                'required',
                'string',
                'in:' . implode(
                    ',',
                    array_map(
                        fn(TraceTimestampEnum $enum) => $enum->value,
                        TraceTimestampEnum::cases()
                    )
                ),
            ],
            ...RequestFilterRules::services(),
            ...RequestFilterRules::loggedTo(),
            ...RequestFilterRules::types(),
            ...RequestFilterRules::tags(),
            ...RequestFilterRules::statuses(),
            ...RequestFilterRules::durationFromTo(),
            ...RequestFilterRules::data(),
            ...RequestFilterRules::hasProfiling(),
        ];
    }
}