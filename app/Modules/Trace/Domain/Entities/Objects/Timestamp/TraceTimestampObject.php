<?php

namespace App\Modules\Trace\Domain\Entities\Objects\Timestamp;

class TraceTimestampObject
{
    public function __construct(
        public string $value,
        public string $title
    ) {
    }
}
