<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Persistence\QueryBuilder;

use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Persistence\Core\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Persistence\Core\QueryBuilder\QueryBuilder
 */
final class QueryBuilderTest extends TestCase
{
    public function testWithAComplexQuery(): void
    {
        $qb = new QueryBuilder();

        $query = $qb
            ->select($this->jsonSelect())
            ->addSelect($this->caseSelect())
            ->addSelect('t1.foo AS f')
            ->addSelect('t1.bar', 'b')
            ->from('table_one', 't1')
            ->leftJoin('table_two', 't2', 't1.t2 = t2.id')
            ->innerJoin('table_three', 't3', 't1.t3 = t3.id')
            ->where('t2.foo > :foo')
            ->andWhere('t2.bar <= :bar')
            ->andWhere('(t3.a OR t3.b) AND t3.string LIKE :like')
            ->groupBy('t1.f')
            ->addGroupBy('t1.b')
            ->addOrderBy('t1.f, t1.b', OrderBy::DESC)
            ->limit(5)
            ->buildQuery()
        ;

        self::assertEqualsCanonicalizing(
            [
                $this->jsonSelect(),
                $this->caseSelect(),
                't1.foo AS f',
                't1.bar AS b',
            ],
            $query->selects,
        );

        self::assertSame('table_one AS t1', $query->from);

        self::assertEqualsCanonicalizing(
            [
                ' INNER JOIN table_three AS t3 ON t1.t3 = t3.id',
                ' LEFT JOIN table_two AS t2 ON t1.t2 = t2.id',
            ],
            $query->joins,
        );

        self::assertEqualsCanonicalizing(
            [
                't2.foo > :foo',
                't2.bar <= :bar',
                '(t3.a OR t3.b) AND t3.string LIKE :like',
            ],
            $query->wheres,
        );

        self::assertEqualsCanonicalizing(
            ['t1.f', 't1.b'],
            $query->groupBys,
        );

        self::assertSame(
            ['t1.f, t1.b DESC'],
            $query->orderBys,
        );

        self::assertSame(5, $query->limit);
    }

    private function jsonSelect(): string
    {
        return <<<'TXT'
json_build_object(
    'a', t1.a,
    'n', json_build_object(
        'b', t.b,
        'c', t.c
    )
) AS json_select
TXT;
    }

    private function caseSelect(): string
    {
        return <<<'TXT'
(
    CASE
        WHEN 0 = count(t1.t2)
        THEN json_build_object()
        ELSE
            json_agg(
                json_build_object(
                    'a', t2.a,
                    'n', t2.b
                )
            )
    END
) AS case_select
TXT;
    }
}
