<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Mutator\Championship;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity\Championship;

final class SessionsFilter implements ChampionshipMutator
{
    public function apply(Championship $championship): void
    {
        foreach ($championship->events() as $event) {
            $event->removeSessionsCancelledOrPostponed();
            $event->mergeQualifyingSessions();
        }
    }
}
