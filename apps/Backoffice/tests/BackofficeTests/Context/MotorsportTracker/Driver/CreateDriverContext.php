<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Driver;

use Kishlin\Apps\Backoffice\MotorsportTracker\Driver\Command\CreateDriverCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateDriverContext extends BackofficeContext
{
    private ?string $firstname = null;
    private ?string $lastname  = null;
    private ?string $country   = null;

    private ?int $commandStatus = null;

    /**
     * @When a client creates the driver :firstname :lastname for the country :country
     */
    public function aClientCreatesADriver(string $firstname, string $lastname, string $country): void
    {
        $this->commandStatus = null;

        $countryId   = self::database()->fixtureId("country.country.{$this->format($country)}");
        $countryCode = self::database()->fetchOne("SELECT code FROM countries WHERE id = '{$countryId}'");

        assert(is_string($countryCode));

        $this->firstname = $firstname;
        $this->lastname  = $lastname;
        $this->country   = $countryCode;

        $commandTester = new CommandTester(
            self::application()->find(CreateDriverCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['firstname' => $this->firstname, 'name' => $this->lastname, 'country' => $this->country]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the driver is saved$/
     */
    public function theDriverIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT *
FROM drivers d
JOIN countries c on d.country = c.id
WHERE d.firstname = :firstname
AND d.name = :lastname
AND c.code = :country
SQL;

        $params = ['lastname' => $this->lastname, 'firstname' => $this->firstname, 'country' => $this->country];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
