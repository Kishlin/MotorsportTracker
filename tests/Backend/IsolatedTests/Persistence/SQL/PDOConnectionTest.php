<?php

/** @noinspection SqlResolve */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Persistence\SQL;

use Kishlin\Backend\Persistence\Core\Query\Query;
use Kishlin\Backend\Persistence\PDO\PDOConnection;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

/**
 * @internal
 * @covers \Kishlin\Backend\Persistence\PDO\PDOConnection
 */
final class PDOConnectionTest extends TestCase
{
    public function testWithAComplexQuery(): void
    {
        $query = new Query(
            selects: [
                $this->jsonSelect(),
                $this->caseSelect(),
                't1.foo AS f',
                't1.bar AS b',
            ],
            from: 'table_one AS t1',
            joins: [
                ' LEFT JOIN table_two AS t2 ON t1.t2 = t2.id',
                ' INNER JOIN table_three AS t3 ON t1.t3 = t3.id',
            ],
            wheres: [
                't2.foo > :foo',
                't2.bar <= :bar',
                '((t3.a OR t3.b) AND t3.string LIKE :like)',
            ],
            groupBys: ['t1.f', 't1.b'],
            orderBys: ['t1.f, t1.b DESC'],
            limit: 5,
        );

        $computed = self::computeQueryWithConnection($query);

        self::assertSame(
            str_replace(["\n", '    '], [' ', ''], $this->expectedComplexQuery()),
            str_replace(["\n", '    '], [' ', ''], $computed),
        );
    }

    public function testItCanCreateASimpleQuery(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
        );

        self::assertQueryComputesTo('SELECT * FROM t;', $query);
    }

    public function testItCanSelectMultipleColumns(): void
    {
        $query = new Query(
            selects: ['t.id, t.o, t.c'],
            from: 't',
        );

        self::assertQueryComputesTo('SELECT t.id, t.o, t.c FROM t;', $query);
    }

    public function testItCanAddMultipleSelects(): void
    {
        $query = new Query(
            selects: ['t.id', 't.o, t.c'],
            from: 't',
        );

        self::assertQueryComputesTo('SELECT t.id, t.o, t.c FROM t;', $query);
    }

    public function testItCanAddAWhereClause(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            wheres: ['t.id = :id'],
        );

        self::assertQueryComputesTo('SELECT * FROM t WHERE (t.id = :id);', $query);
    }

    public function testItCanAddMultipleWhereClauses(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            wheres: [
                't.id = :id',
                't.c > :val',
                't.d BETWEEN :s AND :e',
            ],
        );

        self::assertQueryComputesTo(
            'SELECT * FROM t WHERE (t.id = :id) AND (t.c > :val) AND (t.d BETWEEN :s AND :e);',
            $query,
        );
    }

    public function testItCanAddALeftJoin(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            joins: [' LEFT JOIN o ON t.o = o.id'],
        );

        self::assertQueryComputesTo('SELECT * FROM t LEFT JOIN o ON t.o = o.id;', $query);
    }

    public function testItCanAddAnInnerJoin(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            joins: [' INNER JOIN o ON t.o = o.id'],
        );

        self::assertQueryComputesTo('SELECT * FROM t INNER JOIN o ON t.o = o.id;', $query);
    }

    public function testItCanAddMultipleJoins(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            joins: [
                ' LEFT JOIN l ON t.l = l.id',
                ' INNER JOIN i ON t.i = i.id',
            ],
        );

        self::assertQueryComputesTo('SELECT * FROM t LEFT JOIN l ON t.l = l.id INNER JOIN i ON t.i = i.id;', $query);
    }

    public function testItCanAddAGroupBy(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            groupBys: ['t.id'],
        );

        self::assertQueryComputesTo('SELECT * FROM t GROUP BY t.id;', $query);
    }

    public function testItCanAddMultipleGroupBys(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            groupBys: ['t.id', 't.a', 't.b'],
        );

        self::assertQueryComputesTo('SELECT * FROM t GROUP BY t.id, t.a, t.b;', $query);
    }

    public function testItCanAddAOrderBy(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            orderBys: ['t.id'],
        );

        self::assertQueryComputesTo('SELECT * FROM t ORDER BY t.id;', $query);
    }

    public function testItCanAddMultipleOrderBys(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            orderBys: ['t.id', 't.a, t.b DESC'],
        );

        self::assertQueryComputesTo('SELECT * FROM t ORDER BY t.id, t.a, t.b DESC;', $query);
    }

    public function testItCanAddALimit(): void
    {
        $query = new Query(
            selects: ['*'],
            from: 't',
            limit: 10,
        );

        self::assertQueryComputesTo('SELECT * FROM t LIMIT 10;', $query);
    }

    public static function assertQueryComputesTo(string $expected, Query $query): void
    {
        $actual = self::computeQueryWithConnection($query);

        self::assertSame($expected, $actual);
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

    private static function computeQueryWithConnection(Query $query): string
    {
        $connection = PDOConnection::create('host', 5637, 'db', 'user', 'pass');

        try {
            $method   = new ReflectionMethod($connection, 'computeQuery');
            $computed = $method->invoke($connection, $query);
        } catch (ReflectionException $e) {
            self::fail($e->getMessage());
        }

        assert(is_string($computed));

        return $computed;
    }
}
