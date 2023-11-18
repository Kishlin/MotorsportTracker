<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class RaceLapDetails implements Mapped
{
    private function __construct(
        private int $lap,
        private int $position,
        private bool $pit,
        private int $time,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'lap'      => $this->lap,
            'position' => $this->position,
            'pit'      => $this->pit ? 1 : 0,
            'time'     => $this->time,
        ];
    }

    /**
     * @param array{
     *     position: int,
     *     pit: bool,
     *     time: int,
     * } $data
     */
    public static function fromData(int $lap, array $data): self
    {
        return new self(
            $lap,
            $data['position'],
            $data['pit'],
            $data['time'],
        );
    }
}
