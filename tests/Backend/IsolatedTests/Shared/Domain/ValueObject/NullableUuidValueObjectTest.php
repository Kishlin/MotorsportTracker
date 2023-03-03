<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject
 */
final class NullableUuidValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToString(): void
    {
        self::assertNull((new NullableUuidValueObject(null))->value());

        $valueObject = new NullableUuidValueObject('8eb25a89-af0e-4194-85b5-4b9f6d9aec57');

        self::assertSame('8eb25a89-af0e-4194-85b5-4b9f6d9aec57', $valueObject->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new NullableUuidValueObject('ee638842-a3ed-4bd9-80c9-2804a1e65e7b');

        self::assertNotTrue($reference->equals(new NullableUuidValueObject(null)));

        $shouldBeEqual = new NullableUuidValueObject('ee638842-a3ed-4bd9-80c9-2804a1e65e7b');
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new NullableUuidValueObject('f13338b1-6493-4795-a3c5-943a2f812f74');
        self::assertFalse($reference->equals($shouldNotBeEqual));

        $nullReference         = new NullableUuidValueObject(null);
        $nullThatShouldBeEqual = new NullableUuidValueObject(null);
        self::assertTrue($nullReference->equals($nullThatShouldBeEqual));
    }

    public function testItCanCompareSelfToAUuidValueObject(): void
    {
        $nullableReference = new NullableUuidValueObject('25d2122e-847c-43d5-86a6-f145d396ec1a');

        $shouldBeEqual = new UuidValueObject('25d2122e-847c-43d5-86a6-f145d396ec1a');
        self::assertTrue($nullableReference->equals($shouldBeEqual));

        $shouldNotBeEqual = new UuidValueObject('6dcdc673-d1d3-47db-b2a9-c28faec4cd20');
        self::assertFalse($nullableReference->equals($shouldNotBeEqual));
    }
}
