<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

final class PresentationToApply
{
    private function __construct(
        private readonly string $color,
        private readonly string $icon,
    ) {
    }

    public function color(): string
    {
        return $this->color;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public static function fromScalars(string $color, string $icon): self
    {
        return new self($color, $icon);
    }

    /**
     * @param array{color: string, icon: string} $data
     */
    public static function fromData(array $data): self
    {
        return self::fromScalars($data['color'], $data['icon']);
    }
}
