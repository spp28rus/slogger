<?php

namespace App\Modules\Dashboard\Framework\Http\Resources;

use App\Modules\Common\Framework\Http\Resources\AbstractApiResource;
use App\Modules\Dashboard\Domain\Entities\Objects\DatabaseCollectionStatObject;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;

class DatabaseCollectionResource extends AbstractApiResource
{
    private string $name;
    private float $size;
    private float $indexes_size;
    private float $total_size;
    private int $count;
    private float $avg_obj_size;
    #[OaListItemTypeAttribute(DatabaseCollectionIndexResource::class)]
    private array $indexes;

    public function __construct(DatabaseCollectionStatObject $collection)
    {
        parent::__construct($collection);

        $this->name         = $collection->name;
        $this->size         = $collection->size;
        $this->indexes_size = $collection->indexesSize;
        $this->total_size   = $collection->totalSize;
        $this->count        = $collection->count;
        $this->avg_obj_size = $collection->avgObjSize;
        $this->indexes      = DatabaseCollectionIndexResource::mapIntoMe($collection->indexes);
    }
}
