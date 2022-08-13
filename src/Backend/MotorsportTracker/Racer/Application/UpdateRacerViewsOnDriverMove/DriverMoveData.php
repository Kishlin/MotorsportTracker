<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove;

use DateTimeImmutable;
use Exception;

final class DriverMoveData
{
    private function __construct(
        private string $driverId,
        private string $carId,
        private DateTimeImmutable $date,
    ) {
    }

    public function driverId(): string
    {
        return $this->driverId;
    }

    public function carId(): string
    {
        return $this->carId;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public static function fromScalars(string $driverId, string $carId, DateTimeImmutable $date): self
    {
        return new self($driverId, $carId, $date);
    }

    /**
     * @param array{driver: string, car: string, date: string} $data
     *
     * @throws Exception
     */
    public static function fromData(array $data): self
    {
        return self::fromScalars($data['driver'], $data['car'], new DateTimeImmutable($data['date']));
    }
}
