<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Series\Application\ListSeries;

interface AllSeriesGateway
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function all(): array;
}
