<?php

namespace App\Modules\Trace\Framework\Http\Controllers;

use App\Modules\Trace\Domain\Actions\Interfaces\Mutations\DeleteTraceDynamicIndexActionInterface;
use App\Modules\Trace\Domain\Actions\Interfaces\Queries\FindTraceDynamicIndexesActionInterface;
use App\Modules\Trace\Domain\Actions\Interfaces\Queries\FindTraceDynamicIndexStatsActionInterface;
use App\Modules\Trace\Framework\Http\Resources\TraceDynamicIndexResource;
use App\Modules\Trace\Framework\Http\Resources\TraceDynamicIndexStatsResource;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

readonly class TraceDynamicIndexController
{
    public function __construct(
        private FindTraceDynamicIndexesActionInterface $findTraceDynamicIndexesAction,
        private FindTraceDynamicIndexStatsActionInterface $findTraceDynamicIndexStatsAction,
        private DeleteTraceDynamicIndexActionInterface $deleteTraceDynamicIndexAction
    ) {
    }

    #[OaListItemTypeAttribute(TraceDynamicIndexResource::class)]
    public function index(): AnonymousResourceCollection
    {
        $traces = $this->findTraceDynamicIndexesAction->handle();

        return TraceDynamicIndexResource::collection($traces);
    }

    public function stats(): TraceDynamicIndexStatsResource
    {
        $stats = $this->findTraceDynamicIndexStatsAction->handle();

        return new TraceDynamicIndexStatsResource($stats);
    }

    public function destroy(string $id): void
    {
        $deleted = $this->deleteTraceDynamicIndexAction->handle(
            id: $id
        );

        abort_if(
            boolean: !$deleted,
            code: Response::HTTP_INTERNAL_SERVER_ERROR,
            message: 'Index not deleted'
        );
    }
}
