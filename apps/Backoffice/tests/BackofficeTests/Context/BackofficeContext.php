<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Apps\Backoffice\Kernel as BackofficeKernel;
use Kishlin\Tests\Backend\Tools\ConsoleApplication\ConsoleApplicationInterface;
use Kishlin\Tests\Backend\Tools\Database\DatabaseInterface;
use Kishlin\Tests\Backend\Tools\Environment\SymfonyApplication;

abstract class BackofficeContext implements Context
{
    private static ?SymfonyApplication $symfonyApplication = null;

    /**
     * @BeforeSuite
     * @AfterScenario
     */
    public static function reloadDatabase(): void
    {
        self::coreDatabase()->refreshDatabase();
        self::cacheDatabase()->refreshDatabase();
    }

    /**
     * @AfterScenario
     */
    public static function clearEnvironment(): void
    {
        self::symfonyApplication()->database('cache')->close();
        self::symfonyApplication()->database('core')->close();
        self::symfonyApplication()->clearEnvironment();

        self::$symfonyApplication = null;
    }

    protected function format(string $fixture): string
    {
        return lcfirst(str_replace(' ', '', $fixture));
    }

    protected function countryNameToCode(string $countryName): string
    {
        $countryId   = self::coreDatabase()->fixtureId("country.country.{$this->format($countryName)}");
        $countryCode = self::coreDatabase()->fetchOne("SELECT code FROM countries WHERE id = '{$countryId}'");

        assert(is_string($countryCode));

        return $countryCode;
    }

    protected static function application(): ConsoleApplicationInterface
    {
        return self::symfonyApplication()->application();
    }

    protected static function database(string $database = 'core'): DatabaseInterface
    {
        return self::symfonyApplication()->database($database);
    }

    protected static function coreDatabase(): DatabaseInterface
    {
        return self::symfonyApplication()->database('core');
    }

    protected static function cacheDatabase(): DatabaseInterface
    {
        return self::symfonyApplication()->database('cache');
    }

    private static function symfonyApplication(): SymfonyApplication
    {
        if (null === self::$symfonyApplication) {
            self::$symfonyApplication = new SymfonyApplication(BackofficeKernel::class);
        }

        return self::$symfonyApplication;
    }
}
