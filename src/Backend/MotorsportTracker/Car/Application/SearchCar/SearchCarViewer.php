<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar;

use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;

interface SearchCarViewer
{
    public function search(int $number, string $team, string $championship, int $year): ?CarId;
}
