<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Racer;

use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;

final class RacerContext extends BackofficeContext
{
    /**
     * @Given the racer for :racer exists
     */
    public function theRacerExists(string $racer): void
    {
        self::database()->loadFixture("motorsport.racer.racer.{$this->format($racer)}");
    }

    /**
     * @Then the racer data for :driver is created
     */
    public function theRacerDataIsCreated(string $driver): void
    {
        $query = <<<'SQL'
SELECT r.id
FROM racers r
LEFT JOIN drivers d on r.driver = d.id
WHERE CONCAT(d.firstname, ' ', d.name) = :driver
SQL;

        Assert::assertNotNull(self::database()->fetchOne($query, ['driver' => $driver]));
    }

    /**
     * @Then the racer data for :driver in car :car is from :start to :end
     */
    public function theRacerDataMatchesValues(string $driver, string $car, string $start, string $end): void
    {
        $carId = self::database()->fixtureId("motorsport.car.car.{$this->format($car)}");

        $query = <<<'SQL'
SELECT r.id
FROM racers r
LEFT JOIN drivers d on r.driver = d.id
WHERE CONCAT(d.firstname, ' ', d.name) = :driver
AND r.startdate = :start
AND r.enddate = :end
AND r.car = :carId
SQL;

        $params = ['driver' => $driver, 'carId' => $carId, 'start' => $start, 'end' => $end];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
