<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;

interface SaveSessionTypeGateway
{
    public function save(SessionType $sessionType): void;
}
