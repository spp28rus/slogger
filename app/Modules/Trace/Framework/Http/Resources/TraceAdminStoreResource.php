<?php

namespace App\Modules\Trace\Framework\Http\Resources;

use App\Modules\Common\Framework\Http\Resources\AbstractApiResource;
use App\Modules\Trace\Domain\Entities\Objects\TraceAdminStoreObject;

class TraceAdminStoreResource extends AbstractApiResource
{
    private string $id;
    private string $title;
    private int $store_version;
    private string $store_data;
    private string $created_at;

    public function __construct(TraceAdminStoreObject $resource)
    {
        parent::__construct($resource);

        $this->id            = $resource->id;
        $this->title         = $resource->title;
        $this->store_version = $resource->storeVersion;
        $this->store_data    = $resource->storeData;
        $this->created_at    = $resource->createdAt->toDateTimeString();
    }
}
