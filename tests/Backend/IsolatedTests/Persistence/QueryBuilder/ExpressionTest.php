<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Persistence\QueryBuilder;

use Kishlin\Backend\Persistence\Core\QueryBuilder\ExpressionBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\Comparison
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\ComparisonComparator
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\ExpressionBuilder
 */
final class ExpressionTest extends TestCase
{
    public function testItCanBeEqual(): void
    {
        $sut = (new ExpressionBuilder())->eq('a', 'b');

        self::assertSame('a = b', $sut->__toString());
    }

    public function testItCanBeNotEqual(): void
    {
        $sut = (new ExpressionBuilder())->neq('a', 'b');

        self::assertSame('a <> b', $sut->__toString());
    }

    public function testItCanBeLowerThan(): void
    {
        $sut = (new ExpressionBuilder())->lt('a', 'b');

        self::assertSame('a < b', $sut->__toString());
    }

    public function testItCanBeLowerThanEqual(): void
    {
        $sut = (new ExpressionBuilder())->lte('a', 'b');

        self::assertSame('a <= b', $sut->__toString());
    }

    public function testItCanBeGreaterThan(): void
    {
        $sut = (new ExpressionBuilder())->gt('a', 'b');

        self::assertSame('a > b', $sut->__toString());
    }

    public function testItCanBeGreaterThanEqual(): void
    {
        $sut = (new ExpressionBuilder())->gte('a', 'b');

        self::assertSame('a >= b', $sut->__toString());
    }

    public function testItCanBeLike(): void
    {
        $sut = (new ExpressionBuilder())->like('a', 'b');

        self::assertSame('a LIKE b', $sut->__toString());
    }

    public function testItCanBeOrX(): void
    {
        $sut = (new ExpressionBuilder())->orX('a', 'b');

        self::assertSame('(a OR b)', $sut->__toString());
    }

    public function testItCanBeAnd(): void
    {
        $sut = (new ExpressionBuilder())->andX('a', 'b');

        self::assertSame('(a AND b)', $sut->__toString());
    }
}
