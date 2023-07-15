<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Standing;

interface SaveStandingGateway
{
    public function save(Standing $standing): void;
}
