<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject
 */
final class JsonValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToJson(): void
    {
        self::assertIsArray((new class([]) extends JsonValueObject {})->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class(['first' => ['a' => 20, 'b' => 35], 'second' => true]) extends JsonValueObject {};

        $shouldBeEqual = new class(['first' => ['a' => 20, 'b' => 35], 'second' => true]) extends JsonValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class(['differentKey' => 'wrong value']) extends JsonValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
