<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Event;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\SyncEventCacheCommandHandler;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Event\EventCachedRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Event\EventDataRepositorySpy;

trait EventCachedServicesTrait
{
    private ?EventDataRepositorySpy $eventDataRepositorySpy = null;

    private ?EventCachedRepositorySpy $eventCachedRepositorySpy = null;

    private ?SyncEventCacheCommandHandler $syncEventCacheCommandHandler = null;

    private ?ViewCachedEventsQueryHandler $viewCachedEventsQueryHandler = null;

    public function eventDataRepositorySpy(): EventDataRepositorySpy
    {
        if (null === $this->eventDataRepositorySpy) {
            $this->eventDataRepositorySpy = new EventDataRepositorySpy(
                $this->championshipRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->eventRepositorySpy(),
            );
        }

        return $this->eventDataRepositorySpy;
    }

    public function eventCachedRepositorySpy(): EventCachedRepositorySpy
    {
        if (null === $this->eventCachedRepositorySpy) {
            $this->eventCachedRepositorySpy = new EventCachedRepositorySpy();
        }

        return $this->eventCachedRepositorySpy;
    }

    public function syncEventCacheCommandHandler(): SyncEventCacheCommandHandler
    {
        if (null === $this->syncEventCacheCommandHandler) {
            $this->syncEventCacheCommandHandler = new SyncEventCacheCommandHandler(
                $this->eventCachedRepositorySpy(),
                $this->eventDataRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->syncEventCacheCommandHandler;
    }

    public function viewCachedEventsQueryHandler(): ViewCachedEventsQueryHandler
    {
        if (null === $this->viewCachedEventsQueryHandler) {
            $this->viewCachedEventsQueryHandler = new ViewCachedEventsQueryHandler(
                $this->eventCachedRepositorySpy(),
            );
        }

        return $this->viewCachedEventsQueryHandler;
    }
}
