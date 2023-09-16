<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class EventSessionUpdateSuccessEvent implements ApplicationEvent
{
    private function __construct(
        private UuidValueObject $existingId,
        private NullableDateTimeValueObject $endDate,
        private BoolValueObject $hasResult,
    ) {
    }

    public function existingId(): UuidValueObject
    {
        return $this->existingId;
    }

    public function endDate(): NullableDateTimeValueObject
    {
        return $this->endDate;
    }

    public function hasResult(): BoolValueObject
    {
        return $this->hasResult;
    }

    public static function forData(
        UuidValueObject $existingId,
        NullableDateTimeValueObject $endDate,
        BoolValueObject $hasResult,
    ): self {
        return new self($existingId, $endDate, $hasResult);
    }
}
