<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject;

final readonly class SeasonIdentity
{
    private function __construct(
        private string $id,
        private string $ref,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function ref(): string
    {
        return $this->ref;
    }

    public static function forScalars(string $id, string $ref): self
    {
        return new self($id, $ref);
    }
}
