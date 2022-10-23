<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject
 */
final class FloatValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToInt(): void
    {
        self::assertSame(
            42.0,
            (new class(42.0) extends FloatValueObject {})->value(),
        );
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class(42.0) extends FloatValueObject {};

        $shouldBeEqual = new class(42.0) extends FloatValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class(50.0) extends FloatValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }

    public function testItCanBeCreatedFromOtherInt(): void
    {
        $other = new class(42.0) extends FloatValueObject {};

        self::assertTrue($other::fromOther($other)->equals($other));
    }
}
