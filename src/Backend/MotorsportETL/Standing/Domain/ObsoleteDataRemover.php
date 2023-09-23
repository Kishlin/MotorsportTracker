<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

interface ObsoleteDataRemover
{
    public function removeObsoleteStandingsAndAnalytics(SeasonIdentity $season): void;
}
