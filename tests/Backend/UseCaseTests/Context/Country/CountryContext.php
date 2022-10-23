<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\Country;

use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class CountryContext extends MotorsportTrackerContext
{
    private ?CountryId $countryId       = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->countryRepositorySpy()->clear();
    }

    /**
     * @Given the country :name exists
     *
     * @throws Exception
     */
    public function theCountryExists(string $name): void
    {
        self::container()->fixtureLoader()->loadFixture("country.country.{$this->format($name)}");
    }

    /**
     * @When a client searches for the country with code :code
     */
    public function aClientSearchesForTheCountry(string $code): void
    {
        $this->countryId       = null;
        $this->thrownException = null;

        try {
            /** @var CountryId $countryId */
            $countryId = self::container()->commandBus()->execute(
                CreateCountryIfNotExistsCommand::fromScalars($code),
            );

            $this->countryId = $countryId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the country is saved$/
     */
    public function theCountryIsSaved(): void
    {
        Assert::assertNotNull($this->countryId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->countryRepositorySpy()->has($this->countryId));
    }

    /**
     * @Then /^the country is not recreated$/
     */
    public function theCountryIsNotRecreated(): void
    {
        Assert::assertNotNull($this->countryId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->countryRepositorySpy()->has($this->countryId));

        Assert::assertEquals(1, self::container()->countryRepositorySpy()->count());
    }
}
