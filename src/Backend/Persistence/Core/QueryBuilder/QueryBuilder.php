<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

use Kishlin\Backend\Persistence\Core\Query\Query;
use Stringable;

interface QueryBuilder
{
    public function expr(): ExpressionBuilder;

    public function select(string $key, ?string $alias = null): self;

    public function addSelect(string $key, ?string $alias = null): self;

    public function from(string $key, ?string $alias = null): self;

    public function join(Join $join, string $key, ?string $alias, string|Stringable $criterion): self;

    public function innerJoin(string $key, ?string $alias, string|Stringable $criterion): self;

    public function leftJoin(string $key, ?string $alias, string|Stringable $criterion): self;

    public function where(string|Stringable $expression): self;

    public function andWhere(string|Stringable $expression): self;

    public function groupBy(string $key): self;

    public function addGroupBy(string $key): self;

    public function orderBy(string $key, OrderBy $order = OrderBy::ASC): self;

    public function addOrderBy(string $key, OrderBy $order = OrderBy::ASC): self;

    public function limit(int $limit): self;

    public function withParam(string $key, null|bool|float|int|string $param): self;

    public function buildQuery(): Query;
}
