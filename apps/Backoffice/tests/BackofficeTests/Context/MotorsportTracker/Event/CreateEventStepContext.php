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
    private ?string $dateTime = null;
    private ?string $event    = null;
    private ?string $type     = null;

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

        $this->event    = self::database()->fixtureId("motorsport.event.event.{$this->format($event)}");
        $this->type     = $stepType;
        $this->dateTime = $dateTime;

        $commandTester = new CommandTester(
            self::application()->find(CreateEventStepCommandUsingSymfony::NAME),
        );

        $commandTester->execute([
            'dateTime' => $this->dateTime,
            'event'    => $this->event,
            'type'     => $this->type,
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
WHERE es.date_time = :dateTime
AND es.event = :event
AND es.type = :type
SQL;

        $params = [
            'type'     => self::database()->fixtureId("motorsport.event.stepType.{$this->format($this->type ?? '')}"),
            'dateTime' => $this->dateTime,
            'event'    => $this->event,
        ];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
