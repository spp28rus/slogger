<?php

namespace App\Modules\User\Repository\Dto;

use Illuminate\Support\Carbon;

readonly class UserFullDto
{
    public function __construct(
        public int $id,
        public string $firstName,
        public ?string $lastName,
        public string $email,
        public string $password,
        public string $apiToken,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {
    }
}
