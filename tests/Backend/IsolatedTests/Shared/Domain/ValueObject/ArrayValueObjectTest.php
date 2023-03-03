<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\ArrayValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\ArrayValueObject
 */
final class ArrayValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToArray(): void
    {
        self::assertIsArray((new ArrayValueObject([]))->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new ArrayValueObject([3, 50, 85]);

        $shouldBeEqual = new ArrayValueObject([3, 50, 85]);
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new ArrayValueObject([3, 50]);
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
