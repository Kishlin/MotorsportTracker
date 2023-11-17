<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents;

interface EventsGateway
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function find(string $seriesName, int $year): array;
}
