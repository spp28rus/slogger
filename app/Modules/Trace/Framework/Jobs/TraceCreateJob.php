<?php

namespace App\Modules\Trace\Framework\Jobs;

use App\Modules\Trace\Domain\Actions\Mutations\CreateTraceManyAction;
use App\Modules\Trace\Domain\Entities\Parameters\TraceCreateParametersList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class TraceCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly TraceCreateParametersList $parametersList)
    {
        $this->onConnection(config('queue.queues.creating.connection'))
            ->onQueue(config('queue.queues.creating.name'));
    }

    /**
     * Execute the job.
     */
    public function handle(CreateTraceManyAction $createTraceManyAction): void
    {
        $createTraceManyAction->handle($this->parametersList);
    }
}
