<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;

interface RaceHistoryExtractor
{
    public function extract(SessionIdentity $session): string;
}
