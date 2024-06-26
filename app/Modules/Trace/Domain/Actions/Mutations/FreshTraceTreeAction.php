<?php

namespace App\Modules\Trace\Domain\Actions\Mutations;

use App\Modules\Trace\Domain\Actions\Interfaces\Mutations\FreshTraceTreeActionInterface;
use App\Modules\Trace\Repositories\Interfaces\TraceRepositoryInterface;
use App\Modules\Trace\Repositories\Interfaces\TraceTreeRepositoryInterface;

readonly class FreshTraceTreeAction implements FreshTraceTreeActionInterface
{
    public function __construct(
        private TraceRepositoryInterface $traceRepository,
        private TraceTreeRepositoryInterface $traceTreeRepository
    ) {
    }

    public function handle(): void
    {
        $to = now();

        // TODO: to delete by batch in cycle below
        $this->traceTreeRepository->deleteToLoggedAt(to: $to);

        $page = 1;

        while (true) {
            $trees = $this->traceRepository->findTree(
                page: $page,
                to: $to
            );

            if (!count($trees)) {
                break;
            }

            $this->traceTreeRepository->insertMany($trees);

            ++$page;
        }
    }
}
