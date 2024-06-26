<?php

namespace App\Modules\Cleaner\Domain\Actions;

use App\Modules\Cleaner\Domain\Actions\Interfaces\FindProcessesActionInterface;
use App\Modules\Cleaner\Domain\Entities\Transports\ProcessTransport;
use App\Modules\Cleaner\Repositories\Dto\ProcessDto;
use App\Modules\Cleaner\Repositories\Interfaces\ProcessRepositoryInterface;

readonly class FindProcessesAction implements FindProcessesActionInterface
{
    public function __construct(
        private ProcessRepositoryInterface $processRepository
    ) {
    }

    public function handle(int $page, ?int $settingId = null): array
    {
        return array_map(
            fn(ProcessDto $dto) => ProcessTransport::toObject($dto),
            $this->processRepository->find(
                page: $page,
                settingId: $settingId
            )
        );
    }
}
