<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Environment;

use Exception;
use Kishlin\Apps\MotorsportTracker\Backend\Kernel;
use Kishlin\Tests\Backend\Tools\Database\DatabaseInterface;
use Kishlin\Tests\Backend\Tools\Database\SymfonyPostgresDatabase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class SymfonyApplication
{
    private static ?KernelInterface $kernel = null;

    private static ?DatabaseInterface $database = null;

    private function __construct()
    {
    }

    public static function clearEnvironment(): void
    {
        self::$database = null;
        self::$kernel   = null;
    }

    /**
     * @throws Exception
     */
    public static function handle(Request $request): Response
    {
        return self::kernel()->handle($request);
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
            (new Dotenv())->bootEnv(__DIR__ . '/../../../../../../.env.acceptance');

            self::$kernel = new Kernel('acceptance', true);
            self::$kernel->boot();
        }

        return self::$kernel;
    }
}
