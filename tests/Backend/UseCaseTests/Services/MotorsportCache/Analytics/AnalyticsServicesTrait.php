<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Analytics;

use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\ConstructorAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\UpdateConstructorAnalyticsCacheCommandHandler;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\DriverAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\UpdateDriverAnalyticsCacheCommandHandler;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics\ConstructorAnalyticsForSeasonRepositoryStub;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics\DriverAnalyticsForSeasonRepositoryStub;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\ObjectStoreSpy;

trait AnalyticsServicesTrait
{
    private ?DriverAnalyticsForSeasonGateway $driverAnalyticsForSeasonGateway = null;

    private ?ConstructorAnalyticsForSeasonGateway $constructorAnalyticsForSeasonGateway = null;

    private ?UpdateDriverAnalyticsCacheCommandHandler $updateDriverDriverAnalyticsCacheCommandHandler = null;

    private ?UpdateConstructorAnalyticsCacheCommandHandler $updateConstructorAnalyticsCacheCommandHandler = null;

    abstract public function objectStoreSpy(): ObjectStoreSpy;

    abstract public function cachePersister(): CachePersister;

    public function constructorAnalyticsForSeasonGateway(): ConstructorAnalyticsForSeasonGateway
    {
        if (null === $this->constructorAnalyticsForSeasonGateway) {
            $this->constructorAnalyticsForSeasonGateway = new ConstructorAnalyticsForSeasonRepositoryStub(
                $this->objectStoreSpy(),
            );
        }

        return $this->constructorAnalyticsForSeasonGateway;
    }

    public function driverAnalyticsForSeasonGateway(): DriverAnalyticsForSeasonGateway
    {
        if (null === $this->driverAnalyticsForSeasonGateway) {
            $this->driverAnalyticsForSeasonGateway = new DriverAnalyticsForSeasonRepositoryStub(
                $this->objectStoreSpy(),
            );
        }

        return $this->driverAnalyticsForSeasonGateway;
    }

    public function updateDriverDriverAnalyticsCacheCommandHandler(): UpdateDriverAnalyticsCacheCommandHandler
    {
        if (null === $this->updateDriverDriverAnalyticsCacheCommandHandler) {
            $this->updateDriverDriverAnalyticsCacheCommandHandler = new UpdateDriverAnalyticsCacheCommandHandler(
                $this->driverAnalyticsForSeasonGateway(),
                $this->cachePersister(),
            );
        }

        return $this->updateDriverDriverAnalyticsCacheCommandHandler;
    }

    public function updateConstructorAnalyticsCacheCommandHandler(): UpdateConstructorAnalyticsCacheCommandHandler
    {
        if (null === $this->updateConstructorAnalyticsCacheCommandHandler) {
            $this->updateConstructorAnalyticsCacheCommandHandler = new UpdateConstructorAnalyticsCacheCommandHandler(
                $this->constructorAnalyticsForSeasonGateway(),
                $this->cachePersister(),
            );
        }

        return $this->updateConstructorAnalyticsCacheCommandHandler;
    }
}
