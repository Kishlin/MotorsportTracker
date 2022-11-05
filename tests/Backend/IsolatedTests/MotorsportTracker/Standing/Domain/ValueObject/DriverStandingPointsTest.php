<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Standing\Domain\ValueObject;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints
 */
final class DriverStandingPointsTest extends TestCase
{
    public function testItAcceptsAnInteger(): void
    {
        $valueObject = new DriverStandingPoints(5);

        self::assertSame(5.0, $valueObject->value());
    }

    public function testItAcceptsAFloat(): void
    {
        $valueObject = new DriverStandingPoints(10.0);

        self::assertSame(10.0, $valueObject->value());
    }

    public function testItRefusesNegativeValues(): void
    {
        self::expectException(InvalidValueException::class);

        new DriverStandingPoints(-10.0);
    }
}
