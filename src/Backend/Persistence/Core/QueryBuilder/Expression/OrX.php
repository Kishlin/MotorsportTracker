<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder\Expression;

use Stringable;

final class OrX implements Stringable
{
    private function __construct(
        private readonly Stringable $left,
        private readonly Stringable $right,
    ) {
    }

    public function __toString()
    {
        return "({$this->left}) OR ({$this->right})";
    }

    public function left(): string
    {
        return $this->left->__toString();
    }

    public function right(): string
    {
        return $this->right->__toString();
    }

    public static function build(Stringable $left, Stringable $right): self
    {
        return new self($left, $right);
    }
}
