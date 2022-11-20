<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Result\Domain\ValueObject;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition
 */
final class ResultPositionTest extends TestCase
{
    public function testItCanBeCreatedWithAString(): void
    {
        $vp = new ResultPosition('14');

        self::assertSame('14', $vp->value());
    }

    public function testItCanBeCreatedWithAnInteger(): void
    {
        $vo = new ResultPosition(7);

        self::assertSame('7', $vo->value());
    }

    public function testItCanBeZero(): void
    {
        $vo = new ResultPosition(0);

        self::assertSame('0', $vo->value());
    }

    public function testItCanBeADidNotFinish(): void
    {
        $vo = new ResultPosition(ResultPosition::DID_NOT_FINISH);

        self::assertSame(ResultPosition::DID_NOT_FINISH, $vo->value());
    }

    public function testItCanBeADidNotStart(): void
    {
        $vo = new ResultPosition(ResultPosition::DID_NOT_START);

        self::assertSame(ResultPosition::DID_NOT_START, $vo->value());
    }

    public function testItRefusesInvalidValue(): void
    {
        self::expectException(InvalidValueException::class);

        new ResultPosition('invalid');
    }
}
