<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Venue;

use Kishlin\Apps\Backoffice\MotorsportTracker\Venue\Command\CreateVenueCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateVenueContext extends BackofficeContext
{
    private ?string $country = null;
    private ?string $name    = null;

    private ?int $commandStatus = null;

    /**
     * @Given the venue :name exists
     */
    public function theVenueAlreadyExists(string $name): void
    {
        self::database()->loadFixture("motorsport.venue.venue.{$this->format($name)}");
    }

    /**
     * @When a client creates the venue :name for the :country
     */
    public function aClientCreatesTheVenue(string $name, string $country): void
    {
        $this->commandStatus = null;

        $countryId   = self::database()->fixtureId("country.country.{$this->format($country)}");
        $countryCode = self::database()->fetchOne("SELECT code FROM countries WHERE id = '{$countryId}'");

        assert(is_string($countryCode));

        $this->country = $countryCode;
        $this->name    = $name;

        $commandTester = new CommandTester(
            self::application()->find(CreateVenueCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['name' => $this->name, 'country' => $this->country]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    /**
     * @Then /^the venue is saved$/
     */
    public function theVenueIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT *
FROM venues v
JOIN countries c on v.country = c.id
WHERE v.name = :name
AND c.code = :country
SQL;

        Assert::assertNotNull(
            self::database()->fetchOne($query, ['name' => $this->name, 'country' => $this->country])
        );
    }
}
