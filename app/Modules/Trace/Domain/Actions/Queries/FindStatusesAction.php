<?php

namespace App\Modules\Trace\Domain\Actions\Queries;

use App\Modules\Trace\Domain\Actions\Interfaces\Queries\FindStatusesActionInterface;
use App\Modules\Trace\Domain\Entities\Parameters\TraceFindStatusesParameters;
use App\Modules\Trace\Domain\Entities\Transports\TraceDataFilterTransport;
use App\Modules\Trace\Domain\Entities\Transports\TraceStringFieldTransport;
use App\Modules\Trace\Framework\Http\Controllers\Traits\MakeDataFilterParameterTrait;
use App\Modules\Trace\Repositories\Dto\TraceStringFieldDto;
use App\Modules\Trace\Repositories\Interfaces\TraceContentRepositoryInterface;

readonly class FindStatusesAction implements FindStatusesActionInterface
{
    use MakeDataFilterParameterTrait;

    public function __construct(
        private TraceContentRepositoryInterface $repository
    ) {
    }

    public function handle(TraceFindStatusesParameters $parameters): array
    {
        return array_map(
            fn(TraceStringFieldDto $dto) => TraceStringFieldTransport::toObject($dto),
            $this->repository->findStatuses(
                serviceIds: $parameters->serviceIds,
                text: $parameters->text,
                loggedAtFrom: $parameters->loggingPeriod?->from,
                loggedAtTo: $parameters->loggingPeriod?->to,
                types: $parameters->types,
                tags: $parameters->tags,
                durationFrom: $parameters->durationFrom,
                durationTo: $parameters->durationTo,
                memoryFrom: $parameters->memoryFrom,
                memoryTo: $parameters->memoryTo,
                cpuFrom: $parameters->cpuFrom,
                cpuTo: $parameters->cpuTo,
                data: TraceDataFilterTransport::toDtoIfNotNull($parameters->data),
                hasProfiling: $parameters->hasProfiling,
            )
        );
    }
}
