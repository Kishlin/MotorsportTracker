<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO;

final readonly class StandingsConstructorDTO
{
    /**
     * @param array{
     *     standings: array<int, array{
     *         constructor: array{name: string, uuid: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string}|null,
     *         countryRepresenting: array{name: string, uuid: string, picture: string}|null,
     *         position: int,
     *         points: float,
     *         analytics: array{wins: int},
     *     }>
     * } $data
     */
    private function __construct(
        private array $data,
    ) {
    }

    /**
     * @return array{
     *     standings: array<int, array{
     *         constructor: array{name: string, uuid: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string}|null,
     *         countryRepresenting: array{name: string, uuid: string, picture: string}|null,
     *         position: int,
     *         points: float,
     *         analytics: array{wins: int},
     *     }>
     * }
     */
    public function standings(): array
    {
        return $this->data;
    }

    /**
     * @param array{
     *     standings: array<int, array{
     *         constructor: array{name: string, uuid: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string}|null,
     *         countryRepresenting: array{name: string, uuid: string, picture: string}|null,
     *         position: int,
     *         points: float,
     *         analytics: array{wins: int},
     *     }>
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
