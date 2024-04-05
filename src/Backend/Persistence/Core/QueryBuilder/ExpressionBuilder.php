<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\AndX;
use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\Comparison;
use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\ComparisonComparator;
use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\OrX;
use Stringable;

final class ExpressionBuilder
{
    public function eq(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::EQ, $right);
    }

    public function neq(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::NEQ, $right);
    }

    public function lt(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::LT, $right);
    }

    public function lte(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::LTE, $right);
    }

    public function gt(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::GT, $right);
    }

    public function gte(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::GTE, $right);
    }

    public function like(string|Stringable $left, string|Stringable $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::LIKE, $right);
    }

    public function andX(string|Stringable $left, string|Stringable $right): AndX
    {
        return AndX::build($left, $right);
    }

    public function orX(string|Stringable $left, string|Stringable $right): OrX
    {
        return OrX::build($left, $right);
    }
}
