<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SearchEventRepositorySpy;

trait SearchEventServicesTrait
{
    private ?SearchEventQueryHandler $searchEventQueryHandler = null;

    private ?SearchEventRepositorySpy $searchEventRepositorySpy = null;

    abstract public function eventRepositorySpy(): EventRepositorySpy;

    public function searchEventQueryHandler(): SearchEventQueryHandler
    {
        if (null === $this->searchEventQueryHandler) {
            $this->searchEventQueryHandler = new SearchEventQueryHandler(
                $this->searchEventRepositorySpy(),
            );
        }

        return $this->searchEventQueryHandler;
    }

    public function searchEventRepositorySpy(): SearchEventRepositorySpy
    {
        if (null === $this->searchEventRepositorySpy) {
            $this->searchEventRepositorySpy = new SearchEventRepositorySpy(
                $this->eventRepositorySpy(),
            );
        }

        return $this->searchEventRepositorySpy;
    }
}
