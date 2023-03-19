<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists\CreateAnalyticsIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\AnalyticsRepositorySpy;

trait AnalyticsServicesTrait
{
    private ?AnalyticsRepositorySpy $analyticsRepositorySpy = null;

    private ?CreateAnalyticsIfNotExistsCommandHandler $createAnalyticsIfNotExistsCommandHandler = null;

    public function analyticsRepositorySpy(): AnalyticsRepositorySpy
    {
        if (null === $this->analyticsRepositorySpy) {
            $this->analyticsRepositorySpy = new AnalyticsRepositorySpy();
        }

        return $this->analyticsRepositorySpy;
    }

    public function createAnalyticsIfNotExistsCommandHandler(): CreateAnalyticsIfNotExistsCommandHandler
    {
        if (null === $this->createAnalyticsIfNotExistsCommandHandler) {
            $this->createAnalyticsIfNotExistsCommandHandler = new CreateAnalyticsIfNotExistsCommandHandler(
                $this->analyticsRepositorySpy(),
                $this->analyticsRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createAnalyticsIfNotExistsCommandHandler;
    }
}
