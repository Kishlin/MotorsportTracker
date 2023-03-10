<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateEntryIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $event,
        private readonly string $driver,
        private readonly ?string $team,
        private readonly string $chassis,
        private readonly string $engine,
        private readonly ?string $seriesName,
        private readonly ?string $seriesSlug,
        private readonly string $carNumber,
    ) {
    }

    public function event(): UuidValueObject
    {
        return new UuidValueObject($this->event);
    }

    public function driver(): UuidValueObject
    {
        return new UuidValueObject($this->driver);
    }

    public function team(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->team);
    }

    public function chassis(): StringValueObject
    {
        return new StringValueObject($this->chassis);
    }

    public function engine(): StringValueObject
    {
        return new StringValueObject($this->engine);
    }

    public function seriesName(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->seriesName);
    }

    public function seriesSlug(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->seriesSlug);
    }

    public function carNumber(): StringValueObject
    {
        return new StringValueObject($this->carNumber);
    }

    public static function fromScalars(
        string $event,
        string $driver,
        ?string $team,
        string $chassis,
        string $engine,
        ?string $seriesName,
        ?string $seriesSlug,
        string $carNumber,
    ): self {
        return new self($event, $driver, $team, $chassis, $engine, $seriesName, $seriesSlug, $carNumber);
    }
}
