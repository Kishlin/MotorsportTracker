<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

final readonly class AvailableStandingsDTO
{
    /**
     * @param array{
     *     driverStandings: array<int, null|array{name: string, uuid: string}>,
     *     teamStandings: array<int, null|array{name: string, uuid: string}>,
     *     constructorStandings: array<int, null|array{name: string, uuid: string}>,
     * } $data
     */
    private function __construct(
        private array $data,
    ) {}

    /**
     * @return array<int, null|array{name: string, uuid: string}>
     */
    public function driverStandings(): array
    {
        return $this->data['driverStandings'];
    }

    /**
     * @return array<int, null|array{name: string, uuid: string}>
     */
    public function teamStandings(): array
    {
        return $this->data['teamStandings'];
    }

    /**
     * @return array<int, null|array{name: string, uuid: string}>
     */
    public function constructorStandings(): array
    {
        return $this->data['constructorStandings'];
    }

    /**
     * @param array{
     *     driverStandings: array<int, null|array{name: string, uuid: string}>,
     *     teamStandings: array<int, null|array{name: string, uuid: string}>,
     *     constructorStandings: array<int, null|array{name: string, uuid: string}>,
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
