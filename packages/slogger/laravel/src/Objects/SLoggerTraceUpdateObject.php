<?php

namespace SLoggerLaravel\Objects;

use SLoggerLaravel\Profiling\Dto\SLoggerProfilingObjects;

class SLoggerTraceUpdateObject
{
    public function __construct(
        public string $traceId,
        public ?SLoggerProfilingObjects $profiling = null,
        public ?array $tags = null,
        public ?array $data = null,
    ) {
    }
}
