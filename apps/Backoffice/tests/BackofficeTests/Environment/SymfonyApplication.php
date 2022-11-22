<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Environment;

use Kishlin\Apps\Backoffice\Kernel;
use Kishlin\Tests\Apps\Backoffice\Tools\ConsoleApplication\ConsoleApplicationInterface;
use Kishlin\Tests\Apps\Backoffice\Tools\ConsoleApplication\SymfonyConsoleApplication;
use Kishlin\Tests\Backend\Tools\Database\DatabaseInterface;
use Kishlin\Tests\Backend\Tools\Database\SymfonyPostgresDatabase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\KernelInterface;

final class SymfonyApplication
{
    private static ?KernelInterface $kernel = null;

    private static ?DatabaseInterface $database = null;

    private static ?ConsoleApplicationInterface $application = null;

    private function __construct()
    {
    }

    public static function clearEnvironment(): void
    {
        self::$database    = null;
        self::$application = null;
        self::$kernel      = null;
    }

    public static function application(): ConsoleApplicationInterface
    {
        if (null === self::$application) {
            self::$application = new SymfonyConsoleApplication(self::kernel());
        }

        return self::$application;
    }

    public static function database(): DatabaseInterface
    {
        if (null === self::$database) {
            self::$database = new SymfonyPostgresDatabase(self::kernel());
        }

        return self::$database;
    }

    private static function kernel(): KernelInterface
    {
        if (null === self::$kernel) {
            (new Dotenv())->bootEnv(__DIR__ . '/../../../../../.env.acceptance');

            self::$kernel = new Kernel('acceptance', true);
            self::$kernel->boot();
        }

        return self::$kernel;
    }
}
