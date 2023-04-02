<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Event;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\SyncSeasonEventsCommandHandler;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents\ViewSeasonEventsQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Event\SeasonEventListRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Event\SeasonEventsRepositorySpy;

trait SeasonEventServicesTrait
{
    private ?SeasonEventsRepositorySpy $seasonEventsRepositorySpy = null;

    private ?SeasonEventListRepositorySpy $seasonEventListRepositorySpy = null;

    private ?SyncSeasonEventsCommandHandler $syncSeasonEventsCommandHandler = null;

    private ?ViewSeasonEventsQueryHandler $viewSeasonEventsQueryHandler = null;

    public function seasonEventsRepositorySpy(): SeasonEventsRepositorySpy
    {
        if (null === $this->seasonEventsRepositorySpy) {
            $this->seasonEventsRepositorySpy = new SeasonEventsRepositorySpy();
        }

        return $this->seasonEventsRepositorySpy;
    }

    public function seasonEventListRepositorySpy(): SeasonEventListRepositorySpy
    {
        if (null === $this->seasonEventListRepositorySpy) {
            $this->seasonEventListRepositorySpy = new SeasonEventListRepositorySpy(
                $this->championshipRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->eventRepositorySpy(),
            );
        }

        return $this->seasonEventListRepositorySpy;
    }

    public function syncSeasonEventsCommandHandler(): SyncSeasonEventsCommandHandler
    {
        if (null === $this->syncSeasonEventsCommandHandler) {
            $this->syncSeasonEventsCommandHandler = new SyncSeasonEventsCommandHandler(
                $this->seasonEventListRepositorySpy(),
                $this->seasonEventsRepositorySpy(),
                $this->seasonEventsRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->syncSeasonEventsCommandHandler;
    }

    public function viewSeasonEventsQueryHandler(): ViewSeasonEventsQueryHandler
    {
        if (null === $this->viewSeasonEventsQueryHandler) {
            $this->viewSeasonEventsQueryHandler = new ViewSeasonEventsQueryHandler(
                $this->seasonEventsRepositorySpy(),
            );
        }

        return $this->viewSeasonEventsQueryHandler;
    }
}
