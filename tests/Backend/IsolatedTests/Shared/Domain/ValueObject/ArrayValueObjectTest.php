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
        self::assertIsArray((new class([]) extends ArrayValueObject {
        })->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class([3, 50, 85]) extends ArrayValueObject {
        };

        $shouldBeEqual = new class([3, 50, 85]) extends ArrayValueObject {
        };
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class([3, 50]) extends ArrayValueObject {
        };
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
