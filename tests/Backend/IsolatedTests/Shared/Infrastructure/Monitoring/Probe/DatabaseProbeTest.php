<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Monitoring\Probe;

use Exception;
use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\DatabaseProbe;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\DatabaseProbe
 */
final class DatabaseProbeTest extends TestCase
{
    public function testItIsAliveWhenConnected(): void
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $connection->method('isConnected')->willReturn(true);

        $probe = new DatabaseProbe($connection, 'core');

        self::assertTrue($probe->isAlive());
    }

    public function testItIsNotAliveOnFailedConnection(): void
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $connection->method('connect')->willThrowException(new Exception());

        $probe = new DatabaseProbe($connection, 'core');

        self::assertFalse($probe->isAlive());
    }
}
