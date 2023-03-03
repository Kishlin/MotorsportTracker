<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject
 */
final class StrictlyPositiveIntValueObjectTest extends TestCase
{
    public function testItAcceptsToBePositive(): void
    {
        self::assertSame(
            1,
            (new StrictlyPositiveIntValueObject(1))->value(),
        );
    }

    public function testItRefusesToBeZero(): void
    {
        $zero = 0;

        self::expectException(InvalidValueException::class);
        new StrictlyPositiveIntValueObject($zero);
    }

    public function testItRefusesToBeNegative(): void
    {
        $negativeInt = -1;

        self::expectException(InvalidValueException::class);
        new StrictlyPositiveIntValueObject($negativeInt);
    }
}
