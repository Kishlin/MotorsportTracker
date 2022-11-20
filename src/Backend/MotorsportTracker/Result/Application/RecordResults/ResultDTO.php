<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultRacerId;

final class ResultDTO
{
    private function __construct(
        private string $racerId,
        private int $position,
        private float $points,
    ) {
    }

    public function racerId(): ResultRacerId
    {
        return new ResultRacerId($this->racerId);
    }

    public function position(): ResultPosition
    {
        return new ResultPosition($this->position);
    }

    public function points(): ResultPoints
    {
        return new ResultPoints($this->points);
    }

    public static function fromScalars(string $racerId, int $position, float $points): self
    {
        return new self($racerId, $position, $points);
    }
}
