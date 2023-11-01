<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListSeasons;

interface SeasonsGateway
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function find(string $seriesName): array;
}
