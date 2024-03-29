<?php

namespace App\Modules\TraceAggregator\Dto\Parameters\DataFilter;

readonly class TraceDataFilterParameters
{
    /**
     * @param TraceDataFilterItemParameters[] $filter
     * @param string[]                        $fields
     */
    public function __construct(
        public array $filter = [],
        public array $fields = [],
    ) {
    }
}
