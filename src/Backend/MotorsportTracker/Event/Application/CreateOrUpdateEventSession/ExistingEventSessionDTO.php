<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class ExistingEventSessionDTO
{
    private function __construct(
        private UuidValueObject $id,
        private BoolValueObject $hasResult,
        private NullableDateTimeValueObject $endDate,
    ) {
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function hasResult(): BoolValueObject
    {
        return $this->hasResult;
    }

    public function endDate(): NullableDateTimeValueObject
    {
        return $this->endDate;
    }

    public static function create(
        UuidValueObject $id,
        BoolValueObject $hasResult,
        NullableDateTimeValueObject $endDate,
    ): self {
        return new self($id, $hasResult, $endDate);
    }
}
