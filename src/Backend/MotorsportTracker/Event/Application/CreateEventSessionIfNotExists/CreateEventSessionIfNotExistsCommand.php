<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateEventSessionIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $eventId,
        private readonly string $typeId,
        private readonly bool $hasResult,
        private readonly ?DateTimeImmutable $startDate,
        private readonly ?DateTimeImmutable $endDate,
        private readonly ?string $ref,
    ) {
    }

    public function eventId(): UuidValueObject
    {
        return new UuidValueObject($this->eventId);
    }

    public function typeId(): UuidValueObject
    {
        return new UuidValueObject($this->typeId);
    }

    public function hasResult(): BoolValueObject
    {
        return new BoolValueObject($this->hasResult);
    }

    /**
     * @throws Exception
     */
    public function startDate(): NullableDateTimeValueObject
    {
        return new NullableDateTimeValueObject($this->startDate);
    }

    /**
     * @throws Exception
     */
    public function endDate(): NullableDateTimeValueObject
    {
        return new NullableDateTimeValueObject($this->endDate);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(
        string $eventId,
        string $typeId,
        bool $hasResult,
        ?DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        ?string $ref,
    ): self {
        return new self($eventId, $typeId, $hasResult, $startDate, $endDate, $ref);
    }
}
