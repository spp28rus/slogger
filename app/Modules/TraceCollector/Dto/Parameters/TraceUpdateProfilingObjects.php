<?php

namespace App\Modules\TraceCollector\Dto\Parameters;

class TraceUpdateProfilingObjects
{
    /** @var TraceUpdateProfilingObject[] */
    private array $items = [];

    public function add(TraceUpdateProfilingObject $object): static
    {
        $this->items[] = $object;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
