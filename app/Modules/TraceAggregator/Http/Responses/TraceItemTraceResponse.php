<?php

namespace App\Modules\TraceAggregator\Http\Responses;

use App\Http\Resources\AbstractApiResource;
use App\Modules\TraceAggregator\Dto\Objects\TraceItemTraceObject;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;

class TraceItemTraceResponse extends AbstractApiResource
{
    private ?TraceServiceResponse $service;
    private string $trace_id;
    private ?string $parent_trace_id;
    private string $type;
    private string $status;
    #[OaListItemTypeAttribute('string')]
    private array $tags;
    private ?float $duration;
    private ?float $memory;
    private ?float $cpu;
    #[OaListItemTypeAttribute(TraceDataAdditionalFieldResponse::class)]
    private array $additional_fields;
    private string $logged_at;
    private string $created_at;
    private string $updated_at;

    public function __construct(TraceItemTraceObject $trace)
    {
        parent::__construct($trace);

        $this->service         = TraceServiceResponse::makeIfNotNull($trace->service);
        $this->trace_id        = $trace->traceId;
        $this->parent_trace_id = $trace->parentTraceId;
        $this->type            = $trace->type;
        $this->status          = $trace->status;
        $this->tags            = $trace->tags;
        $this->duration        = $trace->duration;
        $this->memory          = $trace->memory;
        $this->cpu             = $trace->cpu;

        $this->additional_fields = TraceDataAdditionalFieldResponse::mapIntoMe(
            $trace->additionalFields
        );

        $this->logged_at  = $trace->loggedAt->toDateTimeString('microsecond');
        $this->created_at = $trace->createdAt->toDateTimeString('microsecond');
        $this->updated_at = $trace->updatedAt->toDateTimeString('microsecond');
    }
}
