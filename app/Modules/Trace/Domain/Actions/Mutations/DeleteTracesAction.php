<?php

namespace App\Modules\Trace\Domain\Actions\Mutations;

use App\Modules\Trace\Domain\Actions\Interfaces\Mutations\DeleteTracesActionInterface;
use App\Modules\Trace\Domain\Entities\Parameters\DeleteTracesParameters;
use App\Modules\Trace\Domain\Exceptions\TraceDynamicIndexInProcessException;
use App\Modules\Trace\Domain\Exceptions\TraceDynamicIndexNotInitException;
use App\Modules\Trace\Domain\Services\TraceDynamicIndexingActionService;
use App\Modules\Trace\Repositories\Interfaces\TraceRepositoryInterface;
use App\Modules\Trace\Repositories\Services\TraceDynamicIndexInitializer;

readonly class DeleteTracesAction implements DeleteTracesActionInterface
{
    public function __construct(
        private TraceRepositoryInterface $traceRepository,
        private TraceDynamicIndexingActionService $traceDynamicIndexingActionService,
        private TraceDynamicIndexInitializer $traceDynamicIndexInitializer
    ) {
    }

    /**
     * @throws TraceDynamicIndexInProcessException
     * @throws TraceDynamicIndexNotInitException
     */
    public function handle(DeleteTracesParameters $parameters): int
    {
        $this->traceDynamicIndexingActionService->handle(
            fn() => $this->traceDynamicIndexInitializer->init(
                loggedAtTo: $parameters->loggedAtTo,
                types: ['stub'],
            )
        );

        return $this->traceRepository->deleteTraces(
            loggedAtTo: $parameters->loggedAtTo,
            type: $parameters->type,
            excludedTypes: $parameters->excludedTypes
        );
    }
}
