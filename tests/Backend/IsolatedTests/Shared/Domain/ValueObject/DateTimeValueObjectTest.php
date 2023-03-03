<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject
 */
final class DateTimeValueObjectTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCanBeCreatedAndConvertedBackToDateTimeImmutable(): void
    {
        $dateTime = '22-11-1993 01:00';

        self::assertEqualsCanonicalizing(
            new DateTimeImmutable($dateTime),
            (new DateTimeValueObject(new DateTimeImmutable($dateTime)))->value(),
        );
    }

    public function testItCanCompareItselfToAnotherDateTimeImmutable(): void
    {
        $reference = new DateTimeValueObject(new DateTimeImmutable('22-11-1993 01:00'));

        $shouldBeEqual = new DateTimeValueObject(new DateTimeImmutable('22-11-1993 01:00'));
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new DateTimeValueObject(new DateTimeImmutable('12-05-1997 12:00'));
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
