<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Event;

use Kishlin\Apps\Backoffice\MotorsportTracker\Event\Command\CreateEventCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateEventContext extends BackofficeContext
{
    private ?string $championship = null;
    private ?int $year            = null;
    private ?string $label        = null;
    private ?int $index           = null;
    private ?string $venue        = null;

    private ?int $commandStatus = null;

    /**
     * @Given the event :event exists
     */
    public function theEventExists(string $event): void
    {
        self::database()->loadFixture("motorsport.event.event.{$this->format($event)}");
    }

    /**
     * @When a client creates the event :label of index :index for the season :season and venue :venue
     */
    public function aClientCreatesAnEvent(string $label, int $index, string $season, string $venue): void
    {
        $this->commandStatus = null;

        preg_match('/([\w\s]+)\s([\w]+)/', $season, $matches);

        $this->year         = (int) $matches[2];
        $this->championship = $matches[1];
        $this->label        = $label;
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
            'label'        => $this->label,
        ]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the event is saved$/
     */
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
AND e.label = :label
AND s.year = :year
SQL;

        $params = [
            'championship' => $this->championship,
            'year'         => $this->year,
            'index'        => $this->index,
            'label'        => $this->label,
            'venue'        => $this->venue,
        ];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
