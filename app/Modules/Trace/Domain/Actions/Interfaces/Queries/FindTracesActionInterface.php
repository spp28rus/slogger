<?php

namespace App\Modules\Trace\Domain\Actions\Interfaces\Queries;

use App\Modules\Trace\Domain\Entities\Objects\TraceItemObjects;
use App\Modules\Trace\Domain\Entities\Parameters\TraceFindParameters;

interface FindTracesActionInterface
{
    public function handle(TraceFindParameters $parameters): TraceItemObjects;
}
