<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation;

use DateTimeImmutable;
use Exception;

final class EventStepViewData
{
    private function __construct(
        private readonly string $championshipSlug,
        private readonly string $color,
        private readonly string $icon,
        private readonly string $name,
        private readonly string $type,
        private readonly string $venueLabel,
        private readonly DateTimeImmutable $dateTime,
    ) {
    }

    public function championshipSlug(): string
    {
        return $this->championshipSlug;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function venueLabel(): string
    {
        return $this->venueLabel;
    }

    public function dateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public static function fromScalars(
        string $championshipSlug,
        string $color,
        string $icon,
        string $name,
        string $type,
        string $venueLabel,
        DateTimeImmutable $dateTime,
    ): self {
        return new self($championshipSlug, $color, $icon, $name, $type, $venueLabel, $dateTime);
    }

    /**
     * @param array{slug: string, color: string, icon: string, name: string, type: string, venue: string, datetime: string} $data
     *
     * @throws Exception
     */
    public static function fromData(array $data): self
    {
        return self::fromScalars(
            $data['slug'],
            $data['color'],
            $data['icon'],
            $data['name'],
            $data['type'],
            $data['venue'],
            new DateTimeImmutable($data['datetime']),
        );
    }
}
