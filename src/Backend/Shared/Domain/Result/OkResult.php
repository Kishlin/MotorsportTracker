<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Result;

final readonly class OkResult implements Result
{
    private function __construct(
        private null|object $value,
    ) {
    }

    public function isOk(): bool
    {
        return true;
    }

    public function unwrap(): null|object
    {
        return $this->value;
    }

    public function failure(): ?int
    {
        throw new ResultIsSuccessException();
    }

    public static function forValue(null|object $value): self
    {
        return new self($value);
    }

    public static function create(): self
    {
        return new self(null);
    }
}
