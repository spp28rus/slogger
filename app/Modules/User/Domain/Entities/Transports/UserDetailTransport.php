<?php

namespace App\Modules\User\Domain\Entities\Transports;

use App\Modules\User\Domain\Entities\Objects\UserDetailObject;
use App\Modules\User\Repositories\Dto\UserDetailDto;

class UserDetailTransport
{
    public static function toObject(UserDetailDto $dto): UserDetailObject
    {
        return new UserDetailObject(
            id: $dto->id,
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            email: $dto->email,
            password: $dto->password,
            apiToken: $dto->apiToken,
            createdAt: $dto->createdAt,
            updatedAt: $dto->updatedAt
        );
    }
}
