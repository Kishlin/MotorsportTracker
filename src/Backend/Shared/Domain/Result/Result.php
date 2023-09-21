<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Result;

interface Result
{
    public function isOk(): bool;

    public function unwrap(): null|object;

    public function unwrapFailure(): ?int;
}
