<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

interface SeasonListGateway
{
    public function find(StringValueObject $championshipName): SeasonListDTO;
}
