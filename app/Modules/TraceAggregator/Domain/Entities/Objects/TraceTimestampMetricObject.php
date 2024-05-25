<?php

namespace App\Modules\TraceAggregator\Domain\Entities\Objects;

use Illuminate\Support\Carbon;

class TraceTimestampMetricObject
{
    public function __construct(
        public string $key,
        public Carbon $value
    ) {
    }
}
