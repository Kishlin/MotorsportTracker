<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Car;

use Kishlin\Apps\Backoffice\MotorsportTracker\Car\Command\RegisterDriverMoveCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class RegisterDriverMoveContext extends BackofficeContext
{
    private ?string $driver   = null;
    private ?string $dateTime = null;
    private ?string $carId    = null;

    private ?int $commandStatus = null;

    /**
     * @When a client records a driver move for driver :driver to the car :car on date :dateTime
     */
    public function aClientRecordsADriverMove(string $driver, string $car, string $dateTime): void
    {
        $this->commandStatus = null;

        $query = <<<'SQL'
SELECT c.number as number, t.name as team, ch.name as championship, s.year as year
FROM cars c
LEFT JOIN teams t on c.team = t.id
LEFT JOIN seasons s on c.season = s.id
LEFT JOIN championships ch on s.championship = ch.id
WHERE c.id = :id
SQL;

        $this->carId = self::database()->fixtureId("motorsport.car.car.{$this->format($car)}");

        /** @var array{number: int, team: string, championship: string, year: int} $carData */
        $carData = self::database()->fetchAssociative($query, ['id' => $this->carId]);

        ['number' => $number, 'team' => $team, 'championship' => $championship, 'year' => $year] = $carData;

        $this->dateTime = $dateTime;
        $this->driver   = $driver;

        $commandTester = new CommandTester(
            self::application()->find(RegisterDriverMoveCommandUsingSymfony::NAME),
        );

        $commandTester->execute([
            'driver'       => $this->driver,
            'championship' => $championship,
            'year'         => $year,
            'team'         => $team,
            'number'       => $number,
            'datetime'     => $this->dateTime,
        ]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the driver move is recorded$/
     */
    public function theDriverMoveIsRecorded(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT dm.id
FROM driver_moves dm
LEFT JOIN drivers d on dm.driver = d.id
WHERE dm.car = :carId
AND d.name = :driver
AND dm.date = :dateTime
SQL;

        $params = ['carId' => $this->carId, 'driver' => $this->driver, 'dateTime' => $this->dateTime];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
