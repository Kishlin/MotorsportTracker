<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultFastestLapTime;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultRacerId;

final class ResultDTO
{
    private function __construct(
        private string $racerId,
        private string $fastestLapTime,
        private int $position,
        private float $points,
    ) {
    }

    public function racerId(): ResultRacerId
    {
        return new ResultRacerId($this->racerId);
    }

    public function fastestLapTime(): ResultFastestLapTime
    {
        return new ResultFastestLapTime($this->fastestLapTime);
    }

    public function position(): ResultPosition
    {
        return new ResultPosition($this->position);
    }

    public function points(): ResultPoints
    {
        return new ResultPoints($this->points);
    }

    public static function fromScalars(string $racerId, string $fastestLapTime, int $position, float $points): self
    {
        return new self($racerId, $fastestLapTime, $position, $points);
    }
}
