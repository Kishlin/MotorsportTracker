<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject
 */
final class IntValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToInt(): void
    {
        self::assertSame(
            42,
            (new IntValueObject(42))->value(),
        );
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new IntValueObject(42);

        $shouldBeEqual = new IntValueObject(42);
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new IntValueObject(50);
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }

    public function testItCanBeCreatedFromOtherInt(): void
    {
        $other = new IntValueObject(42);

        self::assertTrue($other::fromOther($other)->equals($other));
    }
}
