<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class DriverAnalyticsData implements Mapped
{
    private function __construct(
        private float $avgFinishPosition,
        private int $classWins,
        private int $fastestLaps,
        private int $finalAppearances,
        private int $hatTricks,
        private int $podiums,
        private int $poles,
        private int $racesLed,
        private int $ralliesLed,
        private int $retirements,
        private int $semiFinalAppearances,
        private int $stageWins,
        private int $starts,
        private int $top10s,
        private int $top5s,
        private int $wins,
        private float $winsPercentage,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'avg_finish_position'    => $this->avgFinishPosition,
            'class_wins'             => $this->classWins,
            'fastest_laps'           => $this->fastestLaps,
            'final_appearances'      => $this->finalAppearances,
            'hat_tricks'             => $this->hatTricks,
            'podiums'                => $this->podiums,
            'poles'                  => $this->poles,
            'races_led'              => $this->racesLed,
            'rallies_led'            => $this->ralliesLed,
            'retirements'            => $this->retirements,
            'semi_final_appearances' => $this->semiFinalAppearances,
            'stage_wins'             => $this->stageWins,
            'starts'                 => $this->starts,
            'top10s'                 => $this->top10s,
            'top5s'                  => $this->top5s,
            'wins'                   => $this->wins,
            'wins_percentage'        => $this->winsPercentage,
        ];
    }

    /**
     * @param array{
     *     avgFinishPosition: float,
     *     classWins: int,
     *     fastestLaps: int,
     *     finalAppearances: int,
     *     hatTricks: int,
     *     podiums: int,
     *     poles: int,
     *     racesLed: int,
     *     ralliesLed: int,
     *     retirements: int,
     *     semiFinalAppearances: int,
     *     stageWins: int,
     *     starts: int,
     *     top10s: int,
     *     top5s: int,
     *     wins: int,
     *     winsPercentage: float,
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['avgFinishPosition'],
            $data['classWins'],
            $data['fastestLaps'],
            $data['finalAppearances'],
            $data['hatTricks'],
            $data['podiums'],
            $data['poles'],
            $data['racesLed'],
            $data['ralliesLed'],
            $data['retirements'],
            $data['semiFinalAppearances'],
            $data['stageWins'],
            $data['starts'],
            $data['top10s'],
            $data['top5s'],
            $data['wins'],
            $data['winsPercentage'],
        );
    }
}
