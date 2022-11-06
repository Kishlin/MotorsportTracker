<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Team;

use Kishlin\Apps\Backoffice\MotorsportTracker\Team\CreateTeamCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateTeamContext extends BackofficeContext
{
    private ?string $country = null;
    private ?string $image   = null;
    private ?string $name    = null;

    private ?int $commandStatus = null;

    /**
     * @When a client creates the team :team for the country :country
     */
    public function aClientCreatesTheTeam(string $team, string $country, string $image = 'image'): void
    {
        $this->commandStatus = null;

        $this->country = $this->countryNameToCode($country);
        $this->image   = 'image';
        $this->name    = $team;

        $commandTester = new CommandTester(
            self::application()->find(CreateTeamCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['name' => $this->name, 'image' => $this->image, 'country' => $this->country]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the team is saved$/
     */
    public function theTeamIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT *
FROM teams t
JOIN countries c on t.country = c.id
WHERE t.name = :name
AND t.image = :image
AND c.code = :country
SQL;

        $params = ['name' => $this->name, 'image' => $this->image, 'country' => $this->country];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
