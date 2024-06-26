<?php

namespace App\Modules\Trace\Framework\Http\Controllers;

use App\Modules\Trace\Domain\Actions\Interfaces\Queries\FindTraceTreeActionInterface;
use App\Modules\Trace\Domain\Entities\Parameters\TraceFindTreeParameters;
use App\Modules\Trace\Domain\Exceptions\TreeTooLongException;
use App\Modules\Trace\Framework\Http\Resources\Tree\TraceTreesResource;
use Symfony\Component\HttpFoundation\Response;

readonly class TraceTreeController
{
    public function __construct(
        private FindTraceTreeActionInterface $findTraceTreeAction
    ) {
    }

    public function index(string $traceId): TraceTreesResource
    {
        try {
            $traceTreeNodeObjects = $this->findTraceTreeAction->handle(
                new TraceFindTreeParameters(
                    traceId: $traceId
                )
            );
        } catch (TreeTooLongException $exception) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getMessage());
        }

        return new TraceTreesResource($traceTreeNodeObjects);
    }
}
