<?php

namespace App\Modules\TraceAggregator\Repositories;

use App\Models\Traces\Trace;
use App\Modules\TraceAggregator\Domain\Entities\Parameters\TraceFindStatusesParameters;
use App\Modules\TraceAggregator\Domain\Entities\Parameters\TraceFindTagsParameters;
use App\Modules\TraceAggregator\Domain\Entities\Parameters\TraceFindTypesParameters;
use App\Modules\TraceAggregator\Repositories\Interfaces\TraceContentRepositoryInterface;
use App\Modules\TraceAggregator\Repositories\Services\TraceQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

readonly class TraceContentRepository implements TraceContentRepositoryInterface
{
    public function __construct(
        private TraceQueryBuilder $traceQueryBuilder
    ) {
    }

    public function findTypes(TraceFindTypesParameters $parameters): array
    {
        return $this->traceQueryBuilder
            ->make(
                serviceIds: $parameters->serviceIds,
                loggedAtFrom: $parameters->loggingPeriod?->from,
                loggedAtTo: $parameters->loggingPeriod?->to,
                data: $parameters->data,
                hasProfiling: $parameters->hasProfiling
            )
            ->when(
                $parameters->text,
                fn(Builder $query) => $query->where('type', 'like', "%$parameters->text%")
            )
            ->groupBy('type')
            ->pluck('type')
            ->sort()
            ->toArray();
    }

    public function findTags(TraceFindTagsParameters $parameters): array
    {
        $mql = $this->traceQueryBuilder
            ->make(
                serviceIds: $parameters->serviceIds,
                loggedAtFrom: $parameters->loggingPeriod?->from,
                loggedAtTo: $parameters->loggingPeriod?->to,
                types: $parameters->types,
                data: $parameters->data,
                hasProfiling: $parameters->hasProfiling
            )
            ->toMql();

        $match = [];

        foreach ($mql['find'][0] ?? [] as $key => $value) {
            $match[$key] = $value;
        }

        $pipeline = [];

        if ($match) {
            $pipeline[] = [
                '$match' => $match,
            ];
        }

        $pipeline[] = [
            '$unwind' => [
                'path' => '$tags',
            ],
        ];

        $pipeline[] = [
            '$group' => [
                '_id' => '$tags',
            ],
        ];

        if ($parameters->text) {
            $pipeline[] = [
                '$match' => [
                    '_id' => [
                        '$regex' => "^.*$parameters->text.*$",
                    ],
                ],
            ];
        }

        $pipeline[] = [
            '$limit' => 50,
        ];

        $iterator = Trace::collection()->aggregate($pipeline);

        return collect($iterator)->pluck('_id')->sort()->toArray();
    }

    public function findStatuses(TraceFindStatusesParameters $parameters): array
    {
        return $this->traceQueryBuilder
            ->make(
                serviceIds: $parameters->serviceIds,
                loggedAtFrom: $parameters->loggingPeriod?->from,
                loggedAtTo: $parameters->loggingPeriod?->to,
                types: $parameters->types,
                tags: $parameters->tags,
                data: $parameters->data,
                hasProfiling: $parameters->hasProfiling
            )
            ->when(
                $parameters->text,
                fn(Builder $query) => $query->where('status', 'like', "%$parameters->text%")
            )
            ->groupBy('status')
            ->pluck('status')
            ->sort()
            ->toArray();
    }
}
