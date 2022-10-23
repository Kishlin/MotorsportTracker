<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject
 */
final class PositiveFloatValueObjectTest extends TestCase
{
    public function testItAcceptsToBePositive(): void
    {
        self::assertSame(
            1.0,
            (new class(1.0) extends PositiveFloatValueObject {})->value(),
        );
    }

    public function testItAcceptsToBeZero(): void
    {
        self::assertSame(
            0.0,
            (new class(0.0) extends PositiveFloatValueObject {})->value(),
        );
    }

    public function testItRefusesToBeNegative(): void
    {
        $negativeInt = -1.0;

        self::expectException(InvalidValueException::class);
        new class($negativeInt) extends PositiveFloatValueObject {};
    }
}
