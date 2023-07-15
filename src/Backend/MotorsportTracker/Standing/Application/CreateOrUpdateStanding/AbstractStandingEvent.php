<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

abstract readonly class AbstractStandingEvent implements ApplicationEvent
{
    final private function __construct(
        private UuidValueObject $season,
        private NullableStringValueObject $seriesClass,
        private UuidValueObject $standee,
        private StandingType $standingType,
    ) {
    }

    public function season(): UuidValueObject
    {
        return $this->season;
    }

    public function seriesClass(): NullableStringValueObject
    {
        return $this->seriesClass;
    }

    public function standee(): UuidValueObject
    {
        return $this->standee;
    }

    public function standingType(): StandingType
    {
        return $this->standingType;
    }

    public static function forSeasonClassAndStandee(
        UuidValueObject $season,
        NullableStringValueObject $seriesClass,
        UuidValueObject $standee,
        StandingType $standingType,
    ): self {
        return new static($season, $seriesClass, $standee, $standingType);
    }
}
