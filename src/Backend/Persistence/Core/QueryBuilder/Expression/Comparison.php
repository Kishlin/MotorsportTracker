<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder\Expression;

use Stringable;

final class Comparison implements Stringable
{
    private function __construct(
        private readonly string $left,
        private readonly ComparisonComparator $comparison,
        private readonly string $right,
    ) {
    }

    public function __toString()
    {
        return "{$this->left} {$this->comparison->value} {$this->right}";
    }

    public function left(): string
    {
        return $this->left;
    }

    public function comparison(): ComparisonComparator
    {
        return $this->comparison;
    }

    public function right(): string
    {
        return $this->right;
    }

    public static function build(string $left, ComparisonComparator $comparison, string $right): self
    {
        return new self($left, $comparison, $right);
    }
}
