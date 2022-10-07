<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Domain\View;

final class RacerPOPO
{
    private function __construct(
        private string $racerId,
        private string $driverName,
        private int $carNumber,
    ) {
    }

    public function racerId(): string
    {
        return $this->racerId;
    }

    public function driverName(): string
    {
        return $this->driverName;
    }

    public function carNumber(): int
    {
        return $this->carNumber;
    }

    /**
     * @param array{id: string, driver_name: string, car_number: int} $data
     */
    public static function fromData(array $data): self
    {
        return new self($data['id'], $data['driver_name'], $data['car_number']);
    }

    public static function fromScalars(string $id, string $driverName, int $carNumber): self
    {
        return new self($id, $driverName, $carNumber);
    }
}
