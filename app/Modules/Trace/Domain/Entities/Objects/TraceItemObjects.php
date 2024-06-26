<?php

namespace App\Modules\Trace\Domain\Entities\Objects;

use App\Modules\Common\Domain\Entities\PaginationInfoObject;

readonly class TraceItemObjects
{
    /**
     * @param TraceItemObject[] $items
     */
    public function __construct(
        public array $items,
        public PaginationInfoObject $paginationInfo
    ) {
    }
}
