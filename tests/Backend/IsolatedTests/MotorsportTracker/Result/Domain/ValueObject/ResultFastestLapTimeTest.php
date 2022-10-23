<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Result\Domain\ValueObject;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultFastestLapTime;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultFastestLapTime
 */
final class ResultFastestLapTimeTest extends TestCase
{
    public function testItAcceptsALapTime(): void
    {
        $time = "1'13.652";

        $object = new ResultFastestLapTime($time);

        self::assertSame($time, $object->value());
    }

    public function testItRefusesWronglyFormattedTime(): void
    {
        self::expectException(InvalidValueException::class);
        new ResultFastestLapTime('Not a lap time.');
    }
}
