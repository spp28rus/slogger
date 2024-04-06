<?php

namespace App\Modules\TraceAggregator\Repositories\Dto;

use Illuminate\Support\Carbon;

readonly class TraceDetailDto
{
    public function __construct(
        public ?TraceServiceDto $service,
        public string $traceId,
        public ?string $parentTraceId,
        public string $type,
        public string $status,
        public array $tags,
        public array $data,
        public ?float $duration,
        public ?float $memory,
        public ?float $cpu,
        public Carbon $loggedAt,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {
    }
}
