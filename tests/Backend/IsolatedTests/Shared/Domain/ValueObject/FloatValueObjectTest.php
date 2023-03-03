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
            (new FloatValueObject(42.0))->value(),
        );
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new FloatValueObject(42.0);

        $shouldBeEqual = new FloatValueObject(42.0);
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new FloatValueObject(50.0);
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }

    public function testItCanBeCreatedFromOtherInt(): void
    {
        $other = new FloatValueObject(42.0);

        self::assertTrue($other::fromOther($other)->equals($other));
    }
}
