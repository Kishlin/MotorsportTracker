<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class SeasonDetails implements Mapped
{
    private function __construct(
        private int $year,
        private string $ref,
    ) {}

    public function mappedData(): array
    {
        return [
            'year' => $this->year,
            'ref'  => $this->ref,
        ];
    }

    /**
     * @param array{year: int, uuid: string} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['year'],
            $data['uuid'],
        );
    }
}
