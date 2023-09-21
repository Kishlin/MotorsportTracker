<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Result;

use Kishlin\Backend\Shared\Domain\Result\FailResult;
use Kishlin\Backend\Shared\Domain\Result\ResultIsFailureException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Result\FailResult
 */
final class FailResultTest extends TestCase
{
    public function testItFlagsTheResultAsFailure(): void
    {
        $failResult = FailResult::create();

        self::assertFalse($failResult->isOk());
    }

    public function testItCanReturnAFailureCode(): void
    {
        $code = time();

        $failResult = FailResult::withCode($code);

        self::assertSame($code, $failResult->unwrapFailure());
    }

    public function testItRefusesToUnwrap(): void
    {
        $failResult = FailResult::create();

        self::expectException(ResultIsFailureException::class);
        $failResult->unwrap();
    }
}
