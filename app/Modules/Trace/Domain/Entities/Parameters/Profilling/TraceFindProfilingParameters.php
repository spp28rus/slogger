<?php

namespace App\Modules\Trace\Domain\Entities\Parameters\Profilling;

readonly class TraceFindProfilingParameters
{
    public function __construct(
        public string $traceId,
        public ?string $caller = null,
        public ?array $excludedCallers = null
    ) {
    }
}
