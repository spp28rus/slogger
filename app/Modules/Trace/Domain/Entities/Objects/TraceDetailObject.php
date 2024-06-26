<?php

namespace App\Modules\Trace\Domain\Entities\Objects;

use App\Modules\Trace\Domain\Entities\Objects\Data\TraceDataObject;
use Illuminate\Support\Carbon;

readonly class TraceDetailObject
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        public ?TraceServiceObject $service,
        public string $traceId,
        public ?string $parentTraceId,
        public string $type,
        public string $status,
        public array $tags,
        public TraceDataObject $data,
        public ?float $duration,
        public ?float $memory,
        public ?float $cpu,
        public Carbon $loggedAt,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {
    }
}
