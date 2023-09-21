<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Result;

use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\ResultIsSuccessException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Result\OkResult
 */
final class OkResultTest extends TestCase
{
    public function testItFlagsResultAsOk(): void
    {
        $okResult = OkResult::create();

        self::assertTrue($okResult->isOk());
    }

    public function testItCanUnwrapANullValue(): void
    {
        $okResult = OkResult::forValue(null);

        self::assertNull($okResult->unwrap());
    }

    public function testItCanUnwrapAnObject(): void
    {
        $okResult = OkResult::forValue(new stdClass());

        self::assertInstanceOf(stdClass::class, $okResult->unwrap());
    }

    public function testItRefusesToUnwrapAFailure(): void
    {
        $okResult = OkResult::create();

        self::expectException(ResultIsSuccessException::class);
        $okResult->unwrapFailure();
    }
}
