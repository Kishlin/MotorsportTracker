<?php

/** @noinspection SqlResolve */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Persistence\SQL;

use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Persistence\SQL\SQLQueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Persistence\SQL\SQLQuery
 * @covers \Kishlin\Backend\Persistence\SQL\SQLQueryBuilder
 */
final class SQLQueryBuilderTest extends TestCase
{
    public function testWithAComplexQuery(): void
    {
        $qb = new SQLQueryBuilder();

        $query = $qb
            ->select($this->jsonSelect())
            ->addSelect($this->caseSelect())
            ->addSelect('t1.foo AS f')
            ->addSelect('t1.bar', 'b')
            ->from('table_one', 't1')
            ->leftJoin('table_two', 't2', $qb->expr()->eq('t1.t2', 't2.id'))
            ->innerJoin('table_three', 't3', $qb->expr()->eq('t1.t3', 't3.id'))
            ->where($qb->expr()->gt('t2.foo', ':foo'))
            ->andWhere($qb->expr()->lte('t2.bar', ':bar'))
            ->andWhere($qb->expr()->andX(
                $qb->expr()->orX('t3.a', 't3.b'),
                $qb->expr()->like('t3.string', ':like'),
            ))
            ->groupBy('t1.f')
            ->addGroupBy('t1.b')
            ->addOrderBy('t1.f, t1.b', OrderBy::DESC)
            ->limit(5)
            ->buildQuery()
            ->query()
        ;

        self::assertSame(
            str_replace(["\n", '    '], [' ', ''], $this->expectedComplexQuery()),
            str_replace(["\n", '    '], [' ', ''], $query),
        );
    }

    public function testItCanCreateASimpleQuery(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t;', $query);
    }

    public function testItCanSelectMultipleColumns(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('t.id, t.o, t.c')
            ->from('t')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT t.id, t.o, t.c FROM t;', $query);
    }

    public function testItCanAddMultipleSelects(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('t.id')
            ->addSelect('t.o, t.c')
            ->from('t')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT t.id, t.o, t.c FROM t;', $query);
    }

    public function testItCanAddAWhereClause(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->where($queryBuilder->expr()->eq('t.id', ':id'))
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t WHERE (t.id = :id);', $query);
    }

    public function testItCanAddMultipleWhereClauses(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->where($queryBuilder->expr()->eq('t.id', ':id'))
            ->andWhere($queryBuilder->expr()->gt('t.c', ':val'))
            ->andWhere('t.d BETWEEN :s AND :e')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t WHERE (t.id = :id) AND (t.c > :val) AND (t.d BETWEEN :s AND :e);', $query);
    }

    public function testItCanAddALeftJoin(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->leftJoin('o', null, 't.o = o.id')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t LEFT JOIN o ON t.o = o.id;', $query);
    }

    public function testItCanAddAnInnerJoin(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->innerJoin('o', null, 't.o = o.id')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t INNER JOIN o ON t.o = o.id;', $query);
    }

    public function testItCanAddMultipleJoins(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->leftJoin('l', null, 't.l = l.id')
            ->innerJoin('i', null, 't.i = i.id')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t LEFT JOIN l ON t.l = l.id INNER JOIN i ON t.i = i.id;', $query);
    }

    public function testItCanAddAGroupBy(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->groupBy('t.id')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t GROUP BY t.id;', $query);
    }

    public function testItCanAddMultipleGroupBys(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->groupBy('t.id')
            ->addGroupBy('t.a, t.b')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t GROUP BY t.id, t.a, t.b;', $query);
    }

    public function testItCanAddAOrderBy(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->orderBy('t.id')
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t ORDER BY t.id;', $query);
    }

    public function testItCanAddMultipleOrderBys(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->orderBy('t.id')
            ->addOrderBy('t.a, t.b', OrderBy::DESC)
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t ORDER BY t.id, t.a, t.b DESC;', $query);
    }

    public function testItCanAddALimit(): void
    {
        $queryBuilder = new SQLQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->from('t')
            ->limit(10)
            ->buildQuery()
            ->query()
        ;

        self::assertSame('SELECT * FROM t LIMIT 10;', $query);
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

    private function expectedComplexQuery(): string
    {
        return <<<'SQL'
SELECT
json_build_object(
    'a', t1.a,
    'n', json_build_object(
        'b', t.b,
        'c', t.c
    )
) AS json_select,
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
) AS case_select,
t1.foo AS f,
t1.bar AS b
FROM table_one AS t1
LEFT JOIN table_two AS t2 ON t1.t2 = t2.id
INNER JOIN table_three AS t3 ON t1.t3 = t3.id
WHERE (t2.foo > :foo)
AND (t2.bar <= :bar)
AND (((t3.a OR t3.b) AND t3.string LIKE :like))
GROUP BY t1.f, t1.b
ORDER BY t1.f, t1.b DESC
LIMIT 5;
SQL;
    }
}
