<?php

namespace SLoggerLaravel\Watchers\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SLoggerLaravel\Enums\SLoggerTraceStatusEnum;
use SLoggerLaravel\Enums\SLoggerTraceTypeEnum;
use SLoggerLaravel\Helpers\SLoggerMaskHelper;
use SLoggerLaravel\Watchers\AbstractSLoggerWatcher;

class SLoggerModelWatcher extends AbstractSLoggerWatcher
{
    protected array $masks = [];

    protected function init(): void
    {
        $this->masks = $this->loggerConfig->modelsMasks();
    }

    public function register(): void
    {
        $this->listenEvent('eloquent.*', [$this, 'handleEvent']);
    }

    public function handleEvent(string $eventName, array $eventData): void
    {
        $this->safeHandleWatching(fn() => $this->onHandleEvent($eventName, $eventData));
    }

    protected function onHandleEvent(string $eventName, array $eventData): void
    {
        if (!$this->shouldRecord($eventName)) {
            return;
        }

        /** @var Model $modelInstance */
        $modelInstance = $eventData['model'] ?? $eventData[0];

        $modelClass = $modelInstance::class;

        $action = $this->getAction($eventName);

        $data = [
            'action'  => $action,
            'model'   => $modelClass,
            'key'     => $modelInstance->getKey(),
            'changes' => $this->prepareChanges($modelInstance),
        ];

        $this->processor->push(
            type: SLoggerTraceTypeEnum::Model->value,
            status: SLoggerTraceStatusEnum::Success->value,
            tags: [
                $action,
                $modelClass,
            ],
            data: $data
        );
    }

    protected function getAction(string $event): string
    {
        preg_match('/\.(.*):/', $event, $matches);

        return $matches[1];
    }

    protected function shouldRecord(string $eventName): bool
    {
        return Str::is([
            '*created*',
            '*updated*',
            '*restored*',
            '*deleted*',
            //'*retrieved*', // list
        ], $eventName);
    }

    protected function prepareChanges(Model $modelInstance): ?array
    {
        $changes = $modelInstance->getChanges() ?: null;

        if (!$changes) {
            return $changes;
        }

        $modelClass = $modelInstance::class;

        $masksForAll = array_merge(
            $this->masks['*'] ?? [],
            $this->masks[$modelClass] ?? []
        );

        if (!$masksForAll) {
            return $changes;
        }

        return SLoggerMaskHelper::maskArrayByPatterns($changes, $masksForAll);
    }
}
