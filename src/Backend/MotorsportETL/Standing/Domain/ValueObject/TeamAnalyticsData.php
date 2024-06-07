<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class TeamAnalyticsData implements Mapped
{
    private function __construct(
        private int $classWins,
        private int $fastestLaps,
        private int $finalAppearances,
        private int $finishes1And2,
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
    ) {}

    public function mappedData(): array
    {
        return [
            'class_wins'             => $this->classWins,
            'fastest_laps'           => $this->fastestLaps,
            'final_appearances'      => $this->finalAppearances,
            'finishes_one_and_two'   => $this->finishes1And2,
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
     *     classWins: int,
     *     fastestLaps: int,
     *     finalAppearances: int,
     *     finishes1And2: int,
     *     podiums: int,
     *     poles: int,
     *     qualifies1And2: int,
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
            $data['classWins'],
            $data['fastestLaps'],
            $data['finalAppearances'],
            $data['finishes1And2'],
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
