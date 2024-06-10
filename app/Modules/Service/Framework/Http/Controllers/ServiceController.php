<?php

namespace App\Modules\Service\Framework\Http\Controllers;

use App\Modules\Service\Domain\Actions\Interfaces\FindServicesActionInterface;
use App\Modules\Service\Framework\Http\Resources\ServiceResource;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

readonly class ServiceController
{
    public function __construct(
        private FindServicesActionInterface $findServicesAction
    ) {
    }

    #[OaListItemTypeAttribute(ServiceResource::class)]
    public function index(): AnonymousResourceCollection
    {
        return ServiceResource::collection(
            $this->findServicesAction->handle()
        );
    }
}
