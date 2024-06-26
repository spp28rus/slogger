<?php

namespace App\Modules\Trace\Framework\Http\Resources\Profiling;

use App\Modules\Common\Framework\Http\Resources\AbstractApiResource;
use App\Modules\Trace\Domain\Entities\Objects\Profiling\ProfilingObject;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;

class TraceProfilingResource extends AbstractApiResource
{
    private string $main_caller;
    #[OaListItemTypeAttribute(TraceProfilingItemResource::class)]
    private array $items;

    public function __construct(ProfilingObject $resource)
    {
        parent::__construct($resource);

        $this->main_caller = $resource->mainCaller;
        $this->items       = TraceProfilingItemResource::mapIntoMe($resource->items);
    }
}
