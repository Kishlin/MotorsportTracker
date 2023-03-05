<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateEventCommand implements Command
{
    private function __construct(
        private readonly string $seasonId,
        private readonly string $venueId,
        private readonly int $index,
        private readonly string $slug,
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly ?DateTimeImmutable $startTime,
        private readonly ?DateTimeImmutable $endTime,
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

    public function slug(): StringValueObject
    {
        return new StringValueObject($this->slug);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function shortName(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->shortName);
    }

    public function startTime(): NullableDateTimeValueObject
    {
        return new NullableDateTimeValueObject($this->startTime);
    }

    public function endTime(): NullableDateTimeValueObject
    {
        return new NullableDateTimeValueObject($this->endTime);
    }

    public static function fromScalars(
        string $seasonId,
        string $venueId,
        int $index,
        string $slug,
        string $name,
        ?string $shortName,
        ?DateTimeImmutable $startTime,
        ?DateTimeImmutable $endTime,
    ): self {
        return new self($seasonId, $venueId, $index, $slug, $name, $shortName, $startTime, $endTime);
    }
}
