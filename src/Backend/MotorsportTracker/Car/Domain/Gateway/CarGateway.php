<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;

interface CarGateway
{
    public function save(Car $car): void;
}
