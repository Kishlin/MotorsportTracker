<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder\Expression;

use Stringable;

final class OrX implements Stringable
{
    private function __construct(
        private readonly string|Stringable $left,
        private readonly string|Stringable $right,
    ) {}

    public function __toString()
    {
        return "({$this->left} OR {$this->right})";
    }

    public function left(): string
    {
        return is_string($this->left) ? $this->left : $this->left->__toString();
    }

    public function right(): string
    {
        return is_string($this->right) ? $this->right : $this->right->__toString();
    }

    public static function build(string|Stringable $left, string|Stringable $right): self
    {
        return new self($left, $right);
    }
}
