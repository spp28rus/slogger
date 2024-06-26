<?php

namespace App\Modules\Trace\Framework\Http\Resources\Timestamp;

use App\Modules\Common\Framework\Http\Resources\AbstractApiResource;
use App\Modules\Trace\Domain\Entities\Objects\Timestamp\TraceTimestampsObjects;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;

class TraceTimestampsResource extends AbstractApiResource
{
    private string $loggedAtFrom;
    #[OaListItemTypeAttribute(TraceTimestampResource::class)]
    private array $items;

    public function __construct(TraceTimestampsObjects $resource)
    {
        parent::__construct($resource);

        $this->loggedAtFrom = $resource->loggedAtFrom->toDateTimeString();
        $this->items        = TraceTimestampResource::mapIntoMe($resource->items);
    }
}
