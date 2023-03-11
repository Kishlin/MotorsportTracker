<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Persistence\QueryBuilder;

use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\Expression
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\Comparison
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\ComparisonComparator
 */
final class ExpressionTest extends TestCase
{
    public function testItCanBeEqual(): void
    {
        $sut = (new Expression())->eq('a', 'b');

        self::assertSame('a = b', $sut->__toString());
    }

    public function testItCanBeNotEqual(): void
    {
        $sut = (new Expression())->neq('a', 'b');

        self::assertSame('a <> b', $sut->__toString());
    }

    public function testItCanBeLowerThan(): void
    {
        $sut = (new Expression())->lt('a', 'b');

        self::assertSame('a < b', $sut->__toString());
    }

    public function testItCanBeLowerThanEqual(): void
    {
        $sut = (new Expression())->lte('a', 'b');

        self::assertSame('a <= b', $sut->__toString());
    }

    public function testItCanBeGreaterThan(): void
    {
        $sut = (new Expression())->gt('a', 'b');

        self::assertSame('a > b', $sut->__toString());
    }

    public function testItCanBeGreaterThanEqual(): void
    {
        $sut = (new Expression())->gte('a', 'b');

        self::assertSame('a >= b', $sut->__toString());
    }
}
