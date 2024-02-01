<?php

namespace App\Modules\Traces\Jobs;

use App\Modules\Traces\Dto\Parameters\TraceUpdateParametersList;
use App\Modules\Traces\Services\TracesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class TraceUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly TraceUpdateParametersList $parametersList)
    {
        $this->onConnection(config('queue.queues.creating.connection'))
            ->onQueue(config('queue.queues.creating.name'));
    }

    /**
     * Execute the job.
     */
    public function handle(TracesService $tracesService): void
    {
        $tracesService->updateMany($this->parametersList);
    }
}