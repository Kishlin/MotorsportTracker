<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\EventsFilter;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;

interface SessionWithResultListGateway
{
    /**
     * @return SessionIdentity[]
     */
    public function find(EventsFilter $eventsFilter): array;
}
