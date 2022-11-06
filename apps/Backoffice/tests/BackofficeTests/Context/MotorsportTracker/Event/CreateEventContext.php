<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Event;

use Kishlin\Apps\Backoffice\MotorsportTracker\Event\CreateEventCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateEventContext extends BackofficeContext
{
    private ?string $label  = null;
    private ?int $index     = null;
    private ?string $season = null;
    private ?string $venue  = null;

    private ?int $commandStatus = null;

    /**
     * @When a client creates the event :label of index :index for the season :season and venue :venue
     */
    public function aClientCreatesAnEvent(string $label, int $index, string $season, string $venue): void
    {
        $this->commandStatus = null;

        $this->label  = $label;
        $this->index  = $index;
        $this->venue  = self::database()->fixtureId("motorsport.venue.venue.{$this->format($venue)}");
        $this->season = self::database()->fixtureId("motorsport.championship.season.{$this->format($season)}");

        $commandTester = new CommandTester(
            self::application()->find(CreateEventCommandUsingSymfony::NAME),
        );

        $commandTester->execute([
            'season' => $this->season,
            'venue'  => $this->venue,
            'index'  => $this->index,
            'label'  => $this->label,
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
WHERE e.season = :season
AND e.index = :index
AND e.label = :label
AND e.venue = :venue
SQL;

        $params = [
            'season' => $this->season,
            'index'  => $this->index,
            'label'  => $this->label,
            'venue'  => $this->venue,
        ];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
