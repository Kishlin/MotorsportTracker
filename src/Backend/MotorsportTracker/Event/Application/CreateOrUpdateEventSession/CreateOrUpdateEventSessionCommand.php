<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateOrUpdateEventSessionCommand implements Command
{
    private function __construct(
        private string $eventId,
        private string $typeId,
        private bool $hasResult,
        private ?DateTimeImmutable $startDate,
        private ?DateTimeImmutable $endDate,
        private ?string $ref,
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
