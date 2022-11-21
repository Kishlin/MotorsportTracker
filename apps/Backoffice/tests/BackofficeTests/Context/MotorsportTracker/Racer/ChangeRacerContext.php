<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Racer;

use Kishlin\Apps\Backoffice\MotorsportTracker\Racer\Command\ChangeRacerEndDateCommand;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class ChangeRacerContext extends BackofficeContext
{
    private ?string $championship = null;
    private ?string $driver       = null;
    private ?string $endDate      = null;

    private ?int $commandStatus = null;

    /**
     * @When  a client wants the racer for :driver in championship :championship to end on :endDate
     */
    public function aClientRecordsADriverMove(string $driver, string $championship, string $endDate): void
    {
        $this->commandStatus = null;

        $this->championship = $championship;
        $this->endDate      = $endDate;
        $this->driver       = $driver;

        $commandTester = new CommandTester(
            self::application()->find(ChangeRacerEndDateCommand::NAME),
        );

        $commandTester->execute([
            'driver'       => $this->driver,
            'championship' => $this->championship,
            'endDate'      => $this->endDate,
        ]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then the racer for :racer now ends on :endDate
     */
    public function theRacerNowEndsOn(string $racer, string $endDate): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT r.id
FROM racers r
LEFT JOIN drivers d on r.driver = d.id
LEFT JOIN cars c on r.car = c.id
LEFT JOIN seasons s on c.season = s.id
LEFT JOIN championships ch on s.championship = ch.id
-- WHERE REPLACE(LOWER(CONCAT(ch.name, ch.slug)), ' ', '') LIKE REPLACE(LOWER(:championship), ' ', '')
-- AND CONCAT(d.firstname, ' ', d.name) = :driver
-- AND r.enddate = :endDate
SQL;

        $params = ['championship' => "%{$this->championship}%", 'driver' => $this->driver, 'endDate' => $endDate];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }

    /**
     * @Then /^the request to update the racer is rejected$/
     */
    public function theRequestToUpdateTheRacerHasBeenRejected(): void
    {
        Assert::assertSame(Command::FAILURE, $this->commandStatus);
    }
}
