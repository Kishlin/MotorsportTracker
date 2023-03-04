<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Event;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Apps\Backoffice\MotorsportTracker\Event\Command\CreateEventCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateEventContext extends BackofficeContext
{
    private ?string $championship = null;
    private ?int $year            = null;
    private ?string $name         = null;
    private ?int $index           = null;
    private ?string $venue        = null;

    private ?int $commandStatus = null;

    #[Given('the event :event exists')]
    public function theEventExists(string $event): void
    {
        self::database()->loadFixture("motorsport.event.event.{$this->format($event)}");
    }

    #[When('a client creates the event :name of index :index for the season :season and venue :venue')]
    public function aClientCreatesAnEvent(string $name, int $index, string $season, string $venue): void
    {
        $this->commandStatus = null;

        preg_match('/([\w\s]+)\s([\w]+)/', $season, $matches);

        $this->year         = (int) $matches[2];
        $this->championship = $matches[1];
        $this->name         = $name;
        $this->index        = $index;
        $this->venue        = $venue;

        $commandTester = new CommandTester(
            self::application()->find(CreateEventCommandUsingSymfony::NAME),
        );

        $commandTester->execute([
            'championship' => $this->championship,
            'year'         => $this->year,
            'venue'        => $this->venue,
            'index'        => $this->index,
            'slug'         => $this->name,
            'name'         => $this->name,
            'short-name'   => $this->name,
            'start-date'   => '2022-11-22 00:00:00',
            'end-date'     => '2022-11-22 01:00:00',
        ]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('the event is saved')]
    public function theEventIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT *
FROM events e
JOIN seasons s on e.season = s.id
JOIN championships ch on s.championship = ch.id
WHERE LOWER(REPLACE(' ', '', e.venue)) = LOWER(REPLACE(' ', '', :venue))
AND ch.name = :championship
AND e.index = :index
AND e.name = :name
AND s.year = :year
SQL;

        $params = [
            'championship' => $this->championship,
            'year'         => $this->year,
            'index'        => $this->index,
            'name'         => $this->name,
            'venue'        => $this->venue,
        ];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
