<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Driver;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Apps\Backoffice\MotorsportTracker\Driver\Command\CreateDriverCommandUsingSymfony;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class CreateDriverContext extends BackofficeContext
{
    private ?string $name    = null;
    private ?string $country = null;

    private ?int $commandStatus = null;

    #[Given('the driver :name exists')]
    public function theDriverExists(string $name): void
    {
        self::database()->loadFixture("motorsport.driver.driver.{$this->format($name)}");
    }

    #[When('a client creates the driver :name for the country :country')]
    public function aClientCreatesADriver(string $name, string $country): void
    {
        $this->commandStatus = null;

        $this->country = $this->countryNameToCode($country);
        $this->name    = $name;

        $commandTester = new CommandTester(
            self::application()->find(CreateDriverCommandUsingSymfony::NAME),
        );

        $commandTester->execute(['name' => $this->name, 'country' => $this->country]);

        $this->commandStatus = $commandTester->getStatusCode();
    }

    #[Then('the driver is saved')]
    public function theDriverIsSaved(): void
    {
        Assert::assertSame(Command::SUCCESS, $this->commandStatus);

        $query = <<<'SQL'
SELECT *
FROM drivers d
JOIN countries c on d.country = c.id
WHERE d.name = :name
AND c.code = :country
SQL;

        $params = ['name' => $this->name, 'country' => $this->country];

        Assert::assertNotNull(self::database()->fetchOne($query, $params));
    }
}
