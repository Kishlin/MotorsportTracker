<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\DTO;

final readonly class PossibleStandingClass
{
    private function __construct(
        private string $uuid,
        private string $name,
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param array{name: string, uuid: string} $standing
     */
    public static function forStanding(array $standing): self
    {
        return new self(
            $standing['uuid'],
            $standing['name'],
        );
    }
}
