<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\IntegrationTests\Doctrine;

use Kishlin\Tests\Apps\Backoffice\Tools\KernelTestCaseTrait;
use Kishlin\Tests\Backend\Apps\AbstractIntegrationTests\Doctrine\DatabaseServiceTestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Integration Test to verify the service container holds an active database connection.
 *
 * @see \Kishlin\Tests\Backend\Apps\AbstractIntegrationTests\Doctrine\DatabaseServiceTestCase
 *
 * @internal
 * @coversNothing
 */
final class CoreDatabaseServiceTest extends DatabaseServiceTestCase
{
    use KernelTestCaseTrait;

    /**
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     */
    public function testItHasAnActiveDatabaseConnection(): void
    {
        self::assertItHasAnActiveDatabaseConnection(
            $this->getContainer(),
            'kishlin.app.infrastructure.connection.core'
        );
    }
}
