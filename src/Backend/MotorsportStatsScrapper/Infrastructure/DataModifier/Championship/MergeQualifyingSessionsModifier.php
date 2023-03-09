<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\DataModifier\Championship;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity\Championship;

final class MergeQualifyingSessionsModifier extends AbstractChampionshipModifier
{
    public function apply(Championship $championship): void
    {
        foreach ($championship->events() as $event) {
            $event->mergeQualifyingSessions();
        }
    }
}
