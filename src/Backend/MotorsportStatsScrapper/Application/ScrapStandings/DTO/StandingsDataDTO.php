<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO;

final readonly class StandingsDataDTO
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
    ) {
    }

    /**
     * @return array{
     *     driverStandings: array<int, null|array{name: string, uuid: string}>,
     *     teamStandings: array<int, null|array{name: string, uuid: string}>,
     *     constructorStandings: array<int, null|array{name: string, uuid: string}>,
     * }
     */
    public function standings(): array
    {
        return $this->data;
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
