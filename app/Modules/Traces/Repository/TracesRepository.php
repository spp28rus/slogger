<?php

namespace App\Modules\Traces\Repository;

use App\Models\Traces\Trace;
use App\Modules\Traces\Dto\Parameters\TraceCreateParametersList;
use App\Modules\Traces\Dto\Parameters\TraceUpdateParametersList;
use MongoDB\BSON\UTCDateTime;

class TracesRepository implements TracesRepositoryInterface
{
    public function createMany(TraceCreateParametersList $parametersList): void
    {
        $timestamp = new UTCDateTime(now());

        $operations = [];

        foreach ($parametersList->getItems() as $parameters) {
            $operations[] = [
                'updateOne' => [
                    [
                        'serviceId' => $parameters->serviceId,
                        'traceId'   => $parameters->traceId,
                    ],
                    [
                        '$set'         => [
                            'parentTraceId' => $parameters->parentTraceId,
                            'type'          => $parameters->type,
                            'tags'          => $parameters->tags,
                            'data'          => json_decode($parameters->data, true),
                            'loggedAt'      => new UTCDateTime($parameters->loggedAt),
                            'updatedAt'     => $timestamp,
                        ],
                        '$setOnInsert' => [
                            'createdAt' => $timestamp,
                        ],
                    ],
                    [
                        'upsert' => true,
                    ],
                ],
            ];
        }

        Trace::collection()->bulkWrite($operations);
    }

    public function updateMany(TraceUpdateParametersList $parametersList): void
    {
        $timestamp = new UTCDateTime(now());

        $operations = [];

        foreach ($parametersList->getItems() as $parameters) {
            $operations[] = [
                'updateOne' => [
                    [
                        'serviceId' => $parameters->serviceId,
                        'traceId'   => $parameters->traceId,
                    ],
                    [
                        '$set' => [
                            ...(is_null($parameters->tags)
                                ? []
                                : [
                                    'tags' => $parameters->tags,
                                ]),
                            ...(is_null($parameters->data)
                                ? []
                                : [
                                    'data' => json_decode($parameters->data, true),
                                ]),
                            'updatedAt' => $timestamp,
                        ],
                    ],
                ],
            ];
        }

        Trace::collection()->bulkWrite($operations);
    }
}
