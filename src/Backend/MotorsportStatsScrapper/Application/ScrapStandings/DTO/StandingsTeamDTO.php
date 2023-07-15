<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO;


final readonly class StandingsTeamDTO
{
    /**
     * @param array{
     *     standings: array<int, array{
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         icon: string,
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{},
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
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         icon: string,
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{},
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
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         icon: string,
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{},
     *     }>
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
