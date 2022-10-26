<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships;

final class ChampionshipPOPO
{
    private function __construct(
        private string $id,
        private string $name,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param array{id: string, name: string} $data
     */
    public static function fromData(array $data): self
    {
        return new self($data['id'], $data['name']);
    }

    public static function fromScalars(string $id, string $name): self
    {
        return new self($id, $name);
    }
}
