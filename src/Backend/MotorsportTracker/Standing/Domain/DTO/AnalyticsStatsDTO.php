<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO;

use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class AnalyticsStatsDTO
{
    private function __construct(
        private readonly float $avgFinishPosition,
        private readonly int $classWins,
        private readonly int $fastestLaps,
        private readonly int $finalAppearances,
        private readonly int $hatTricks,
        private readonly int $podiums,
        private readonly int $poles,
        private readonly int $racesLed,
        private readonly int $ralliesLed,
        private readonly int $retirements,
        private readonly int $semiFinalAppearances,
        private readonly int $stageWins,
        private readonly int $starts,
        private readonly int $top10s,
        private readonly int $top5s,
        private readonly int $wins,
        private readonly float $winsPercentage,
    ) {
    }

    public function avgFinishPosition(): FloatValueObject
    {
        return new FloatValueObject($this->avgFinishPosition);
    }

    public function classWins(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->classWins);
    }

    public function fastestLaps(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->fastestLaps);
    }

    public function finalAppearances(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->finalAppearances);
    }

    public function hatTricks(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->hatTricks);
    }

    public function podiums(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->podiums);
    }

    public function poles(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->poles);
    }

    public function racesLed(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->racesLed);
    }

    public function ralliesLed(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->ralliesLed);
    }

    public function retirements(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->retirements);
    }

    public function semiFinalAppearances(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->semiFinalAppearances);
    }

    public function stageWins(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->stageWins);
    }

    public function starts(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->starts);
    }

    public function top10s(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->top10s);
    }

    public function top5s(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->top5s);
    }

    public function wins(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->wins);
    }

    public function winsPercentage(): FloatValueObject
    {
        return new FloatValueObject($this->winsPercentage);
    }

    public static function fromScalars(
        float $avgFinishPosition,
        int $classWins,
        int $fastestLaps,
        int $finalAppearances,
        int $hatTricks,
        int $podiums,
        int $poles,
        int $racesLed,
        int $ralliesLed,
        int $retirements,
        int $semiFinalAppearances,
        int $stageWins,
        int $starts,
        int $top10s,
        int $top5s,
        int $wins,
        float $winsPercentage,
    ): self {
        return new self(
            $avgFinishPosition,
            $classWins,
            $fastestLaps,
            $finalAppearances,
            $hatTricks,
            $podiums,
            $poles,
            $racesLed,
            $ralliesLed,
            $retirements,
            $semiFinalAppearances,
            $stageWins,
            $starts,
            $top10s,
            $top5s,
            $wins,
            $winsPercentage,
        );
    }
}
