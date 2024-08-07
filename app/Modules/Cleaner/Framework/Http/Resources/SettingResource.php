<?php

namespace App\Modules\Cleaner\Framework\Http\Resources;

use App\Modules\Cleaner\Domain\Entities\Objects\SettingObject;
use App\Modules\Common\Framework\Http\Resources\AbstractApiResource;

class SettingResource extends AbstractApiResource
{
    private int $id;
    private int $days_lifetime;
    private ?string $type;
    private bool $only_data;
    private bool $deleted;
    private string $created_at;
    private string $updated_at;

    public function __construct(SettingObject $resource)
    {
        parent::__construct($resource);

        $this->id            = $resource->id;
        $this->days_lifetime = $resource->daysLifetime;
        $this->deleted       = $resource->deleted;
        $this->type          = $resource->type;
        $this->only_data     = $resource->onlyData;
        $this->created_at    = $resource->createdAt->toDateTimeString();
        $this->updated_at    = $resource->updatedAt->toDateTimeString();
    }
}
