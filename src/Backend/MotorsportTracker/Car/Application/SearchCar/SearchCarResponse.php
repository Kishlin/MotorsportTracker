<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchCarResponse implements Response
{
    private function __construct(
        private CarId $carId
    ) {
    }

    public function carId(): CarId
    {
        return $this->carId;
    }

    public static function fromScalars(CarId $carId): self
    {
        return new self($carId);
    }
}
