<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Racer;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerRepositorySpy;
use PHPUnit\Framework\Assert;

final class RacerContext extends MotorsportTrackerContext
{
    private const DATE_SEASON_START = '1993-01-01';
    private const DATE_SEASON_END   = '1993-12-31';

    private const DATE_SEASON_FIRST_PART_END    = '1993-06-30 23:59:59';
    private const DATE_SEASON_SECOND_PART_START = '1993-07-01 00:00:00';

    public function clearGatewaySpies(): void
    {
        $this->racerRepositorySpy()->clear();
    }

    /**
     * @When /^a racer exists for the driver and car$/
     */
    public function aRacerExists(): void
    {
        $this->racerRepositorySpy()->save(Racer::instance(
            new RacerId(self::RACER_ID),
            new RacerDriverId(self::DRIVER_ID),
            new RacerCarId(self::CAR_ID),
            new RacerStartDate(new DateTimeImmutable(self::DATE_SEASON_START)),
            new RacerEndDate(new DateTimeImmutable(self::DATE_SEASON_END)),
        ));
    }

    /**
     * @Then /^the racer data is created$/
     */
    public function theRacerDataIsCreated(): void
    {
        Assert::assertTrue($this->racerRepositorySpy()->hasOneForDriver(self::DRIVER_ID));
    }

    /**
     * @Then /^the racer data is split at the time of the driver move$/
     */
    public function theRacerDataIsSplit(): void
    {
        $racers = $this->racerRepositorySpy()->findForDriver(self::DRIVER_ID);

        Assert::assertCount(2, $racers);

        $firstRacer = array_shift($racers);

        Assert::assertEquals(self::CAR_ID, $firstRacer?->carId()->value());
        Assert::assertEquals(self::DATE_SEASON_FIRST_PART_END, $firstRacer?->endDate()->value()->format('Y-m-d H:i:s'));

        $secondRacer = array_shift($racers);

        Assert::assertEquals(self::CAR_ID_ALT, $secondRacer?->carId()->value());
        Assert::assertEquals(self::DATE_SEASON_SECOND_PART_START, $secondRacer?->startDate()->value()->format('Y-m-d H:i:s'));
    }

    private function racerRepositorySpy(): RacerRepositorySpy
    {
        return self::container()->serviceContainer()->racerRepositorySpy();
    }
}
