<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents;

interface ResultCounter
{
    public function resultsForEvent(string $event): int;
}
