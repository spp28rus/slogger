<?php

namespace App\Modules\Cleaner\Repositories\Dto;

use Illuminate\Support\Carbon;

readonly class ProcessDto
{
    public function __construct(
        public string $id,
        public int $settingId,
        public int $clearedCount,
        public ?Carbon $clearedAt,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {
    }
}
