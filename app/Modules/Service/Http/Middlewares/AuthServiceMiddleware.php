<?php

namespace App\Modules\Service\Http\Middlewares;

use App\Modules\Service\Http\ServiceContainer;
use App\Modules\Service\Services\ServiceService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\TerminableInterface;

readonly class AuthServiceMiddleware implements TerminableInterface
{
    public function __construct(
        private ServiceService $serviceService,
        private ServiceContainer $serviceContainer
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            abort(401);
        }

        $service = $this->serviceService->findByToken($token);

        if (!$service) {
            abort(401);
        }

        $this->serviceContainer->setService($service);

        return $next($request);
    }


    public function terminate(\Symfony\Component\HttpFoundation\Request $request, Response $response)
    {
        $this->serviceContainer->setService(null);
    }
}
