<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\Comparison;
use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\ComparisonComparator;
use Kishlin\Backend\Persistence\Core\QueryBuilder\Expression\OrX;
use Stringable;

final class Expression
{
    public function eq(string $left, string $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::EQ, $right);
    }

    public function neq(string $left, string $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::NEQ, $right);
    }

    public function lt(string $left, string $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::LT, $right);
    }

    public function lte(string $left, string $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::LTE, $right);
    }

    public function gt(string $left, string $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::GT, $right);
    }

    public function gte(string $left, string $right): Comparison
    {
        return Comparison::build($left, ComparisonComparator::GTE, $right);
    }

    public function orX(Stringable $left, Stringable $right): OrX
    {
        return OrX::build($left, $right);
    }
}
