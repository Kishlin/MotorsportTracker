<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\SymfonyApp\IntegrationTests\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Tests\Apps\SymfonyApp\Tools\KernelTestCaseTrait;
use Kishlin\Tests\Backend\Apps\AbstractIntegrationTests\Doctrine\CoreDatabaseServiceTestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Integration Test to verify the service container holds an active database connection.
 *
 * @see \Kishlin\Tests\Backend\Apps\AbstractIntegrationTests\Doctrine\CoreDatabaseServiceTestCase
 *
 * @internal
 * @coversNothing
 */
final class CoreDatabaseServiceTest extends CoreDatabaseServiceTestCase
{
    use KernelTestCaseTrait;

    /**
     * @throws ContainerExceptionInterface|Exception|NotFoundExceptionInterface
     */
    public function testItHasAnActiveDatabaseConnection(): void
    {
        self::assertItHasAnActiveDatabaseConnection($this->getContainer());
    }
}
