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
        self::assertIsArray((new JsonValueObject([]))->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new JsonValueObject(['first' => ['a' => 20, 'b' => 35], 'second' => true]);

        $shouldBeEqual = new JsonValueObject(['first' => ['a' => 20, 'b' => 35], 'second' => true]);
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new JsonValueObject(['differentKey' => 'wrong value']);
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
