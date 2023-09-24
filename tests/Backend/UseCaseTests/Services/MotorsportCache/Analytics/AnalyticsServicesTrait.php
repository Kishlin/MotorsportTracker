<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Analytics;

use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\DriverAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\UpdateDriverAnalyticsCacheCommandHandler;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics\DriverAnalyticsForSeasonRepositoryStub;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\ObjectStoreSpy;

trait AnalyticsServicesTrait
{
    private ?DriverAnalyticsForSeasonGateway $driverAnalyticsForSeasonGateway = null;

    private ?UpdateDriverAnalyticsCacheCommandHandler $updateDriverDriverAnalyticsCacheCommandHandler = null;

    abstract public function objectStoreSpy(): ObjectStoreSpy;

    abstract public function cachePersister(): CachePersister;

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
}
