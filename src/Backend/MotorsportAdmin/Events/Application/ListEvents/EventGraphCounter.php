<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents;

interface EventGraphCounter
{
    public function graphsForEvent(string $event): int;
}
