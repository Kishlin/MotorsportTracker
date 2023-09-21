<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Result;

final readonly class FailResult implements Result
{
    private function __construct(
        private ?int $code,
    ) {
    }

    public function isOk(): bool
    {
        return false;
    }

    public function unwrap(): null|object
    {
        throw new ResultIsFailureException();
    }

    public function failure(): ?int
    {
        return $this->code;
    }

    public static function withCode(int $code): self
    {
        return new self($code);
    }

    public static function create(): self
    {
        return new self(null);
    }
}
