<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Car;

use Kishlin\Apps\Backoffice\MotorsportTracker\Car\Command\CreateCarCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateCarContext extends BackofficeContext
{
    private ?string $championship = null;
    private ?int $year            = null;
    private ?string $team         = null;
    private ?int $number          = null;

    private ?int $commandStatus = null;

    /**
     * @Given the car :name exists
     */
    public function theCarExists(string $name): void
    {
        self::database()->loadFixture("motorsport.car.car.{$this->format($name)}");
    }

    /**
     * @When a client registers the car :number for the team :team and season :season
     * @When a client creates the car number :number of team :team for the year :year of championship :championship
     */
    public function aClientCreatesTheTeam(int $number, string $team, string $season): void
    {
        $this->commandStatus = null;

        preg_match('/([\w\s]+)\s([\w]+)/', $season, $matches);

        $this->year         = (int) $matches[2];
        $this->championship = $matches[1];
        $this->number       = $number;
        $this->team         = $team;

        $commandTester = new CommandTester(
            self::application()->find(CreateCarCommandUsingSymfony::NAME),
        );

        $commandTester->execute([
            'championship' => $this->championship,
            'number'       => $this->number,
            'year'         => $this->year,
            'team'         => $this->team,
        ]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the car is saved$/
     */
    public function theCarIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT c.id
FROM cars c
JOIN teams t on c.team = t.id
JOIN seasons s on c.season = s.id
JOIN championships ch on s.championship = ch.id
WHERE ch.name = :championship
AND c.number = :number
AND t.name = :team
AND s.year = :year
SQL;

        $params = [
            'championship' => $this->championship,
            'number'       => $this->number,
            'year'         => $this->year,
            'team'         => $this->team,
        ];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
