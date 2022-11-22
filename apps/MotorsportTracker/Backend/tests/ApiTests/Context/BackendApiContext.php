<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context;

use Behat\Behat\Context\Context;
use Exception;
use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Environment\SymfonyApplication;
use Kishlin\Tests\Backend\Tools\Database\DatabaseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BackendApiContext implements Context
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
     * @AfterScenario
     */
    public static function clearEnvironment(): void
    {
        SymfonyApplication::database()->close();
        SymfonyApplication::clearEnvironment();
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
        return SymfonyApplication::handle($request);
    }

    protected static function database(): DatabaseInterface
    {
        return SymfonyApplication::database();
    }
}
