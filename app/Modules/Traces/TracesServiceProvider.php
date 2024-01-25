<?php

namespace App\Modules\Traces;

use App\Modules\Services\Adapters\ServicesHttpAdapter;
use App\Modules\Traces\Http\Controllers\TraceCreateController;
use App\Modules\Traces\Repository\TracesRepository;
use App\Modules\Traces\Repository\TracesRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TracesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRepository();
        $this->registerRoutes();
    }

    private function registerRepository(): void
    {
        $this->app->singleton(TracesRepositoryInterface::class, TracesRepository::class);
    }

    private function registerRoutes(): void
    {
        $servicesHttpAdapter = $this->app->make(ServicesHttpAdapter::class);

        Route::middleware($servicesHttpAdapter->getRequestMiddleware())
            ->prefix('traces-api')
            ->as('traces-api.')
            ->group(function () {
                Route::post('', TraceCreateController::class);
            });
    }
}