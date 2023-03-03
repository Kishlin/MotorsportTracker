<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject
 */
final class StringValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToString(): void
    {
        self::assertSame(
            'project',
            (new StringValueObject('project'))->value(),
        );
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new StringValueObject('project');

        $shouldBeEqual = new StringValueObject('project');
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new StringValueObject('app');
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
