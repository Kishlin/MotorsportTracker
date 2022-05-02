<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\Country;

use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class CountryCreationContext extends MotorsportTrackerContext
{
    private const COUNTRY_CODE = 'fr';

    private ?CountryId $countryId       = null;
    private ?Throwable $thrownException = null;

    public function clearGatewaySpies(): void
    {
        self::container()->countryRepositorySpy()->clear();
    }

    /**
     * @Given /^a country exists$/
     *
     * @throws Exception
     */
    public function aCountryExists(): void
    {
        self::container()->countryRepositorySpy()->save(Country::create(
            new CountryId(self::COUNTRY_ID),
            new CountryCode(self::COUNTRY_CODE),
        ));
    }

    /**
     * @When /^a client searches a country which does not exist$/
     * @When /^a client searches for the existing country$/
     */
    public function createCountryIfNotExists(): void
    {
        $this->countryId       = null;
        $this->thrownException = null;

        try {
            /** @var CountryId $countryId */
            $countryId = self::container()->commandBus()->execute(
                CreateCountryIfNotExistsCommand::fromScalars(self::COUNTRY_CODE),
            );

            $this->countryId = $countryId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    /**
     * @Then /^the new country is saved$/
     */
    public function theCountryIsSaved(): void
    {
        Assert::assertNotNull($this->countryId);
        Assert::assertNull($this->thrownException);

        Assert::assertTrue(self::container()->countryRepositorySpy()->has($this->countryId));
    }

    /**
     * @Then /^the country was not recreated$/
     */
    public function theNewCountryWasNotRecreated(): void
    {
        $this->theCountryIsSaved();

        Assert::assertEquals(1, self::container()->countryRepositorySpy()->count());
    }
}
