<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Result\Domain\ValueObject;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints
 */
final class ResultPointTest extends TestCase
{
    public function testItCanBeUsedWithAnInteger(): void
    {
        $points = new ResultPoints(0);

        self::assertSame(0.0, $points->value());
    }

    public function testItCanBeUsedWithAFloat(): void
    {
        $points = new ResultPoints(0.0);

        self::assertSame(0.0, $points->value());
    }
}
