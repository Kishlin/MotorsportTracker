<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateEventSessionCommand implements Command
{
    private function __construct(
        private readonly string $eventId,
        private readonly string $typeId,
        private readonly string $slug,
        private readonly bool $hasResult,
        private readonly ?DateTimeImmutable $startDate,
        private readonly ?DateTimeImmutable $endDate,
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

    public function slug(): StringValueObject
    {
        return new StringValueObject($this->slug);
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

    public static function fromScalars(
        string $eventId,
        string $typeId,
        string $slug,
        bool $hasResult,
        ?DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
    ): self {
        return new self($eventId, $typeId, $slug, $hasResult, $startDate, $endDate);
    }
}
