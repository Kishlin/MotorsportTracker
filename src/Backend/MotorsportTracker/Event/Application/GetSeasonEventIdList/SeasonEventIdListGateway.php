<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList;

use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

interface SeasonEventIdListGateway
{
    public function find(
        StringValueObject $championshipName,
        PositiveIntValueObject $year,
        NullableStringValueObject $eventFilter,
    ): SeasonEventIdListDTO;
}
