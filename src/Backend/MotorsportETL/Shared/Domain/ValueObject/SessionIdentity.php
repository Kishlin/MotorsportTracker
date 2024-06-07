<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject;

final readonly class SessionIdentity
{
    private function __construct(
        private string $id,
        private string $ref,
        private string $season,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function ref(): string
    {
        return $this->ref;
    }

    public function season(): string
    {
        return $this->season;
    }

    /**
     * @param array{id: string, ref: string, season: string} $datum
     */
    public static function fromData(array $datum): self
    {
        return new self($datum['id'], $datum['ref'], $datum['season']);
    }
}
