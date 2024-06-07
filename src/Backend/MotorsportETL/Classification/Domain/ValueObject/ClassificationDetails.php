<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class ClassificationDetails implements Mapped
{
    private function __construct(
        private int $finishPosition,
        private ?int $gridPosition,
        private int $laps,
        private float $points,
        private float $time,
        private ?string $classifiedStatus,
        private float $averageLapSpeed,
        private ?float $fastestLapTime,
    ) {}

    public function mappedData(): array
    {
        return [
            'finish_position'   => $this->finishPosition,
            'grid_position'     => $this->gridPosition,
            'laps'              => $this->laps,
            'points'            => $this->points,
            'lap_time'          => $this->time,
            'classified_status' => $this->classifiedStatus,
            'average_lap_speed' => $this->averageLapSpeed,
            'fastest_lap_time'  => $this->fastestLapTime,
        ];
    }

    /**
     * @param array{
     *     finishPosition: int,
     *     gridPosition: ?int,
     *     laps: int,
     *     points: float,
     *     time: float,
     *     classifiedStatus: ?string,
     *     avgLapSpeed: float,
     *     fastestLapTime: ?float,
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['finishPosition'],
            $data['gridPosition'],
            $data['laps'],
            $data['points'],
            $data['time'],
            $data['classifiedStatus'],
            $data['avgLapSpeed'],
            $data['fastestLapTime'],
        );
    }
}
