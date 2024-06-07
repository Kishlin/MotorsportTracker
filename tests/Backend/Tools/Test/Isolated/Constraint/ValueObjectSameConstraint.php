<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Isolated\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

final class ValueObjectSameConstraint extends Constraint
{
    public function __construct(
        private mixed $expected,
    ) {}

    public function toString(): string
    {
        return ' holds the value ' . $this->exporter()->export($this->expected);
    }

    /**
     * @param object $other
     */
    protected function matches($other): bool
    {
        return method_exists($other, 'value') && $other->value() === $this->expected;
    }

    /**
     * @param object $other
     */
    protected function additionalFailureDescription($other): string
    {
        if (false === method_exists($other, 'value')) {
            return 'Object does not have a `value` method.';
        }

        return 'Found the held value to be ' . $this->exporter()->export($other->value()) . '.';
    }
}
