<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\AbstractIntegrationTests\Persistence;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Integration Test to verify the service container holds an active database connection.
 *
 * @internal
 * @coversNothing
 */
abstract class DatabaseServiceTestCase extends WebTestCase
{
    /**
     * @param ContainerInterface $container The service container which should hold an active database service
     * @param string             $serviceId The id of the service. Defaults to Kishlin\Backend\Persistence\Core\Connection\Connection if auto-wired.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     */
    protected static function assertItHasAnActiveDatabaseConnection(
        ContainerInterface $container,
        string $serviceId = Connection::class
    ): void {
        $connection = $container->get($serviceId);

        if (null === $connection) {
            self::fail('Failed to get the database service from the container.');
        }

        if (false === $connection instanceof Connection) {
            self::fail('The service is not an instance of ' . Connection::class);
        }

        $connection->connect();

        self::assertTrue($connection->isConnected());
    }
}
