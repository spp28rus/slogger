<?php

namespace App\Modules\TraceCleaner\Http\Resources;

use App\Http\Resources\AbstractApiResource;
use App\Modules\TraceCleaner\Services\Objects\SettingObject;

class SettingResource extends AbstractApiResource
{
    private int $id;
    private int $days_lifetime;
    private ?string $type;
    private string $created_at;
    private string $updated_at;

    public function __construct(SettingObject $resource)
    {
        parent::__construct($resource);

        $this->id            = $resource->id;
        $this->days_lifetime = $resource->daysLifetime;
        $this->type          = $resource->type;
        $this->created_at    = $resource->createdAt->toDateTimeString();
        $this->updated_at    = $resource->updatedAt->toDateTimeString();
    }
}
