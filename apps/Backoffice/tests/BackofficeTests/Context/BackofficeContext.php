<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Environment\SymfonyApplication;
use Kishlin\Tests\Apps\Backoffice\Tools\ConsoleApplication\ConsoleApplicationInterface;
use Kishlin\Tests\Apps\Backoffice\Tools\Database\DatabaseInterface;

abstract class BackofficeContext implements Context
{
    /**
     * @BeforeSuite
     * @AfterScenario
     */
    public static function reloadDatabase(): void
    {
        self::database()->refreshDatabase();
    }

    /**
     * @AfterFeature
     */
    public static function clearEnvironment(): void
    {
        SymfonyApplication::clearEnvironment();
    }

    protected function format(string $fixture): string
    {
        return lcfirst(str_replace(' ', '', $fixture));
    }

    protected function countryNameToCode(string $countryName): string
    {
        $countryId   = self::database()->fixtureId("country.country.{$this->format($countryName)}");
        $countryCode = self::database()->fetchOne("SELECT code FROM countries WHERE id = '{$countryId}'");

        assert(is_string($countryCode));

        return $countryCode;
    }

    protected static function application(): ConsoleApplicationInterface
    {
        return SymfonyApplication::application();
    }

    protected static function database(): DatabaseInterface
    {
        return SymfonyApplication::database();
    }
}
