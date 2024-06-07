<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Result;

final readonly class OkResult implements Result
{
    private function __construct(
        private ?object $value,
    ) {}

    public function isOk(): bool
    {
        return true;
    }

    public function unwrap(): ?object
    {
        return $this->value;
    }

    public function unwrapFailure(): ?int
    {
        throw new ResultIsSuccessException();
    }

    public static function forValue(?object $value): self
    {
        return new self($value);
    }

    public static function create(): self
    {
        return new self(null);
    }
}
