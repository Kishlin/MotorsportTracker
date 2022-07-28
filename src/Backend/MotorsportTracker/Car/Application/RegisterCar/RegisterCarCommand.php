<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarTeamId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class RegisterCarCommand implements Command
{
    private function __construct(
        private int $carNumber,
        private string $teamId,
        private string $seasonId,
    ) {
    }

    public function carNumber(): CarNumber
    {
        return new CarNumber($this->carNumber);
    }

    public function teamId(): CarTeamId
    {
        return new CarTeamId($this->teamId);
    }

    public function seasonId(): CarSeasonId
    {
        return new CarSeasonId($this->seasonId);
    }

    public static function fromScalars(int $carNumber, string $teamId, string $seasonId): self
    {
        return new self($carNumber, $teamId, $seasonId);
    }
}
