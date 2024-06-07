<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class ClassificationGaps implements Mapped
{
    private function __construct(
        private float $timeToLead,
        private float $timeToNext,
        private int $lapsToLead,
        private int $lapsToNext,
    ) {}

    public function mappedData(): array
    {
        return [
            'gap_time_to_lead' => $this->timeToLead,
            'gap_time_to_next' => $this->timeToNext,
            'gap_laps_to_lead' => $this->lapsToLead,
            'gap_laps_to_next' => $this->lapsToNext,
        ];
    }

    /**
     * @param array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['timeToLead'],
            $data['timeToNext'],
            $data['lapsToLead'],
            $data['lapsToNext'],
        );
    }
}
