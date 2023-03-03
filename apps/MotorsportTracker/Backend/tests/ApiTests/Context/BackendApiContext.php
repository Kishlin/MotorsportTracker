<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context;

use Behat\Behat\Context\Context;
use Exception;
use Kishlin\Apps\MotorsportTracker\Backend\Kernel as BackendKernel;
use Kishlin\Tests\Backend\Tools\Database\DatabaseInterface;
use Kishlin\Tests\Backend\Tools\Environment\SymfonyApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BackendApiContext implements Context
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

    public function fixtureId(string $fixture): string
    {
        return self::database()->fixtureId($fixture);
    }

    protected function format(string $fixture): string
    {
        return lcfirst(str_replace(' ', '', $fixture));
    }

    /**
     * @throws Exception
     */
    protected static function handle(Request $request): Response
    {
        return self::symfonyApplication()->handle($request);
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
            self::$symfonyApplication = new SymfonyApplication(BackendKernel::class);
        }

        return self::$symfonyApplication;
    }
}
