<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

final class AndX
{
    private function __construct(
        private readonly Expression $right,
        private readonly Expression $left,
    ) {
    }

    public function right(): Expression
    {
        return $this->right;
    }

    public function left(): Expression
    {
        return $this->left;
    }

    public static function build(Expression $left, Expression $right): self
    {
        return new self($left, $right);
    }
}
