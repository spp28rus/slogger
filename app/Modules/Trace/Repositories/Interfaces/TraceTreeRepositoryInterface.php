<?php

namespace App\Modules\Trace\Repositories\Interfaces;

use App\Modules\Trace\Repositories\Dto\TraceTreeDto;
use Illuminate\Support\Carbon;

interface TraceTreeRepositoryInterface
{
    /**
     * @param TraceTreeDto[] $parametersList
     */
    public function insertMany(array $parametersList): void;

    public function findParentTraceId(string $traceId): ?string;

    /**
     * @return string[]
     */
    public function findTraceIdsInTreeByParentTraceId(string $traceId): array;

    /**
     * @param string[] $ids
     *
     * @return int - number of deleted records
     */
    public function deleteByTraceIds(array $ids): int;

    public function deleteToLoggedAt(Carbon $to): void;
}
