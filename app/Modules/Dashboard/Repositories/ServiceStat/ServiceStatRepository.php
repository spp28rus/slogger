<?php

namespace App\Modules\Dashboard\Repositories\ServiceStat;

use App\Models\Traces\Trace;
use App\Modules\Dashboard\Repositories\ServiceStat\Dto\ServiceStatDto;
use MongoDB\BSON\UTCDateTime;

readonly class ServiceStatRepository implements ServiceStatRepositoryInterface
{
    public function find(): array
    {
        $hours = [
            1,
            3,
            4,
            8,
            8,
            24,
            24 * 2,
            24 * 3,
            24 * 4,
            24 * 5,
            24 * 6,
        ];

        $periods = [];

        $now = now()->endOfHour();

        $preHour = 0;

        foreach ($hours as $hour) {
            $periods[] = [
                $now->clone()->subHours($preHour),
                $now->clone()->subHours($hour - 1)->startOfHour(),
            ];

            $preHour = $hour;
        }

        $stats = [];

        foreach ($periods as $period) {
            $pipeline = [];

            $from = $period[1];
            $to   = $period[0];

            $pipeline[] = [
                '$match' => [
                    'loggedAt' => [
                        '$gte' => new UTCDateTime($from),
                        '$lte' => new UTCDateTime($to),
                    ],
                ],
            ];

            $pipeline[] = [
                '$group' => [
                    '_id'   => [
                        'serviceId' => '$serviceId',
                        'type'      => '$type',
                        'status'    => '$status',
                    ],
                    'count' => [
                        '$sum' => 1,
                    ],
                ],
            ];

            $pipeline[] = [
                '$sort' => [
                    '_id.serviceId' => 1,
                    '_id.type'      => 1,
                    '_id.status'    => 1,
                ],
            ];

            $documents = Trace::collection()->aggregate($pipeline)->toArray();

            foreach ($documents as $document) {
                $stats[] = new ServiceStatDto(
                    from: $from,
                    to: $to,
                    serviceId: $document->_id->serviceId,
                    type: $document->_id->type,
                    status: $document->_id->status,
                    count: $document->count
                );
            }
        }

        return $stats;
    }
}
