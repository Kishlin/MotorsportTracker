<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Event;

use Kishlin\Apps\Backoffice\MotorsportTracker\Event\Command\CreateEventStepCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateEventStepContext extends BackofficeContext
{
    private ?string $championship = null;
    private ?int $year            = null;
    private ?string $event        = null;
    private ?string $dateTime     = null;
    private ?string $type         = null;

    private ?int $commandStatus = null;

    /**
     * @Given the eventStep :eventStep exists
     */
    public function theEventStepExists(string $eventStep): void
    {
        self::database()->loadFixture("motorsport.event.eventStep.{$this->format($eventStep)}");
    }

    /**
     * @When a client creates the :stepType step for the event :event at :dateTime
     */
    public function aClientCreatesAnEventStepForTheEventAndStepType(string $stepType, string $event, string $dateTime): void
    {
        $this->commandStatus = null;

        $eventId = self::database()->fixtureId("motorsport.event.event.{$this->format($event)}");

        $query = <<<'SQL'
SELECT e.label as event, c.name as championship, s.year
FROM events e
LEFT JOIN seasons s on e.season = s.id
LEFT JOIN championships c on s.championship = c.id
WHERE e.id = :eventId
SQL;

        /** @var array{championship: string, year: int, event: string} $data */
        $data = self::database()->fetchAssociative($query, ['eventId' => $eventId]);

        [
            'championship' => $this->championship,
            'year'         => $this->year,
            'event'        => $this->event,
        ] = $data;

        $this->type     = $stepType;
        $this->dateTime = $dateTime;

        $commandTester = new CommandTester(
            self::application()->find(CreateEventStepCommandUsingSymfony::NAME),
        );

        $commandTester->execute([
            'championship' => $this->championship,
            'year'         => $this->year,
            'event'        => $this->event,
            'dateTime'     => $this->dateTime,
            'type'         => $this->type,
        ]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the event step is saved$/
     */
    public function theEventStepIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT *
FROM event_steps es
LEFT JOIN events e on es.event = e.id
LEFT JOIN seasons s on e.season = s.id
LEFT JOIN championships c on s.championship = c.id
WHERE es.date_time = :dateTime
AND c.name = :championship
AND e.label = :event
AND es.type = :type
AND s.year = :year
SQL;

        $params = [
            'type'         => self::database()->fixtureId("motorsport.event.stepType.{$this->format($this->type ?? '')}"),
            'championship' => $this->championship,
            'dateTime'     => $this->dateTime,
            'event'        => $this->event,
            'year'         => $this->year,
        ];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
