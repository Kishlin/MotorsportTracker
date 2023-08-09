<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists\CreateAnalyticsDriversIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\AnalyticsRepositorySpyDriversDrivers;

trait AnalyticsServicesTrait
{
    private ?AnalyticsRepositorySpyDriversDrivers $analyticsRepositorySpy = null;

    private ?CreateAnalyticsDriversIfNotExistsCommandHandler $createAnalyticsIfNotExistsCommandHandler = null;

    public function analyticsRepositorySpy(): AnalyticsRepositorySpyDriversDrivers
    {
        if (null === $this->analyticsRepositorySpy) {
            $this->analyticsRepositorySpy = new AnalyticsRepositorySpyDriversDrivers();
        }

        return $this->analyticsRepositorySpy;
    }

    public function createAnalyticsIfNotExistsCommandHandler(): CreateAnalyticsDriversIfNotExistsCommandHandler
    {
        if (null === $this->createAnalyticsIfNotExistsCommandHandler) {
            $this->createAnalyticsIfNotExistsCommandHandler = new CreateAnalyticsDriversIfNotExistsCommandHandler(
                $this->analyticsRepositorySpy(),
                $this->analyticsRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createAnalyticsIfNotExistsCommandHandler;
    }
}
