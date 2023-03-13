<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateEventIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $seasonId,
        private readonly string $venueId,
        private readonly int $index,
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly ?string $shortCode,
        private readonly ?DateTimeImmutable $startTime,
        private readonly ?DateTimeImmutable $endTime,
        private readonly ?string $ref,
    ) {
    }

    public function seasonId(): UuidValueObject
    {
        return new UuidValueObject($this->seasonId);
    }

    public function venueId(): UuidValueObject
    {
        return new UuidValueObject($this->venueId);
    }

    public function index(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->index);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function shortName(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->shortName);
    }

    public function shortCode(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->shortCode);
    }

    public function startTime(): NullableDateTimeValueObject
    {
        return new NullableDateTimeValueObject($this->startTime);
    }

    public function endTime(): NullableDateTimeValueObject
    {
        return new NullableDateTimeValueObject($this->endTime);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(
        string $seasonId,
        string $venueId,
        int $index,
        string $name,
        ?string $shortName,
        ?string $shortCode,
        ?DateTimeImmutable $startTime,
        ?DateTimeImmutable $endTime,
        ?string $ref,
    ): self {
        return new self($seasonId, $venueId, $index, $name, $shortName, $shortCode, $startTime, $endTime, $ref);
    }
}
