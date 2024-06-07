<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class RaceLapGaps implements Mapped
{
    private function __construct(
        private ?int $timeToLead,
        private ?int $timeToNext,
        private ?int $lapsToLead,
        private ?int $lapsToNext,
    ) {}

    public function mappedData(): array
    {
        return [
            'time_to_lead' => $this->timeToLead,
            'time_to_next' => $this->timeToNext,
            'laps_to_lead' => $this->lapsToLead,
            'laps_to_next' => $this->lapsToNext,
        ];
    }

    /**
     * @param array{timeToLead: ?int, timeToNext: ?int, lapsToLead: ?int, lapsToNext: ?int} $data
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
