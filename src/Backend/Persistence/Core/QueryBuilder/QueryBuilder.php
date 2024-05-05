<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

use Kishlin\Backend\Persistence\Core\Query\Query;
use Stringable;

final class QueryBuilder
{
    private ExpressionBuilder $expression;

    /** @var array<int, string> */
    private array $selects = [];

    private ?string $from = null;

    /** @var array<int, string> */
    private array $joins = [];

    /** @var array<int, string> */
    private array $wheres = [];

    /** @var array<int, string> */
    private array $groupBys = [];

    /** @var array<int, string> */
    private array $orderBys = [];

    private ?int $limit = null;

    /** @var array<string, null|bool|float|int|string> */
    private array $params = [];

    public function __construct()
    {
        $this->expression = new ExpressionBuilder();
    }

    public function expr(): ExpressionBuilder
    {
        return $this->expression;
    }

    public function select(string $key, ?string $alias = null): self
    {
        return $this->addSelect($key, $alias);
    }

    public function addSelect(string $key, ?string $alias = null): self
    {
        $this->selects[] = null === $alias ? $key : "{$key} AS {$alias}";

        return $this;
    }

    public function from(string $key, ?string $alias = null): self
    {
        $this->from = null === $alias ? $key : "{$key} AS {$alias}";

        return $this;
    }

    public function join(Join $join, string $key, ?string $alias, Stringable|string $criterion): self
    {
        $sql = " {$join->value} JOIN {$key}";

        if (null !== $alias) {
            $sql .= " AS {$alias}";
        }

        $sql .= ' ON ' . $criterion;

        $this->joins[] = $sql;

        return $this;
    }

    public function innerJoin(string $key, ?string $alias, Stringable|string $criterion): self
    {
        return $this->join(Join::INNER, $key, $alias, $criterion);
    }

    public function leftJoin(string $key, ?string $alias, Stringable|string $criterion): self
    {
        return $this->join(Join::LEFT, $key, $alias, $criterion);
    }

    public function where(Stringable|string $expression): self
    {
        return $this->andWhere($expression);
    }

    public function andWhere(Stringable|string $expression): self
    {
        $this->wheres[] = (string) $expression;

        return $this;
    }

    public function groupBy(string $key): self
    {
        return $this->addGroupBy($key);
    }

    public function addGroupBy(string $key): self
    {
        $this->groupBys[] = $key;

        return $this;
    }

    public function orderBy(string $key, OrderBy $order = OrderBy::ASC): self
    {
        return $this->addOrderBy($key, $order);
    }

    public function addOrderBy(string $key, OrderBy $order = OrderBy::ASC): self
    {
        $this->orderBys[] = OrderBy::ASC === $order ? $key : "{$key} {$order->value}";

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function withParam(string $key, null|float|bool|int|string $param): self
    {
        $this->params[$key] = $param;

        return $this;
    }

    public function buildQuery(): Query
    {
        return new Query(
            $this->selects,
            $this->from,
            $this->joins,
            $this->wheres,
            $this->groupBys,
            $this->orderBys,
            $this->limit,
            $this->params,
        );
    }
}
