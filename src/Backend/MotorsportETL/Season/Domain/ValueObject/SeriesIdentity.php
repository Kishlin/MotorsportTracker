<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class SeriesIdentity implements Mapped
{
    private function __construct(
        private string $id,
        private string $ref,
    ) {}

    public function id(): UuidValueObject
    {
        return new UuidValueObject($this->id);
    }

    public function ref(): UuidValueObject
    {
        return new UuidValueObject($this->ref);
    }

    public function mappedData(): array
    {
        return [
            'series' => $this->id,
        ];
    }

    public static function forScalars(string $id, string $ref): self
    {
        return new self($id, $ref);
    }
}
