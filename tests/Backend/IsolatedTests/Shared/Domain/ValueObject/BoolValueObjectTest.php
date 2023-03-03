<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject
 */
final class BoolValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToBoolean(): void
    {
        self::assertFalse((new BoolValueObject(false))->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new BoolValueObject(true);

        $shouldBeEqual = new BoolValueObject(true);
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new BoolValueObject(false);
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
