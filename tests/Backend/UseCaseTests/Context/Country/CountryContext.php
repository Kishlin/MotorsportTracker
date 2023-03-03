<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\Country;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class CountryContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $countryId = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->countryRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the country :name exists')]
    public function theCountryExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("country.country.{$this->format($name)}");
    }

    #[When('a client searches for the country with code :code and name :name')]
    public function aClientSearchesForTheCountry(string $code, string $name): void
    {
        $this->countryId       = null;
        $this->thrownException = null;

        try {
            /** @var UuidValueObject $countryId */
            $countryId = self::container()->commandBus()->execute(
                CreateCountryIfNotExistsCommand::fromScalars($code, $name),
            );

            $this->countryId = $countryId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the country is saved')]
    public function theCountryIsSaved(): void
    {
        Assert::assertNotNull($this->countryId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->countryRepositorySpy()->has($this->countryId));
    }

    #[Then('the country is not recreated')]
    public function theCountryIsNotRecreated(): void
    {
        Assert::assertNotNull($this->countryId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->countryRepositorySpy()->has($this->countryId));

        Assert::assertEquals(1, self::container()->countryRepositorySpy()->count());
    }
}
