<?php

namespace App\Modules\TraceAggregator\Framework\Http\Responses;

use App\Modules\Common\Http\Resources\AbstractApiResource;
use App\Modules\TraceAggregator\Domain\Entities\Objects\TraceTreeObjects;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;

class TraceTreesResponse extends AbstractApiResource
{
    private int $tracesCount;
    #[OaListItemTypeAttribute(TraceTreeResponse::class, isRecursive: true)]
    private array $items;

    public function __construct(TraceTreeObjects $trees)
    {
        parent::__construct($trees);

        $this->tracesCount = $trees->tracesCount;
        $this->items       = TraceTreeResponse::mapIntoMe($trees->items);
    }
}
