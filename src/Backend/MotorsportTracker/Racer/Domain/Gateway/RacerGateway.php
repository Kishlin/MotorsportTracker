<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;

interface RacerGateway
{
    public function save(Racer $racer): void;
}
