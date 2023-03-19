<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason;

final class ConstructorStandingsResponse
{
    /**
     * @param array{
     *     standings: array<array{
     *         constructor: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             wins: int,
     *         },
     *     }>,
     * } $data
     */
    private function __construct(
        private readonly array $data,
    ) {
    }

    /**
     * @return array{
     *     standings: array<array{
     *         constructor: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             wins: int,
     *         },
     *     }>,
     * }
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array{
     *     standings: array<array{
     *         constructor: array{name: string, uuid: string, picture: string},
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         countryRepresenting: array{name: string, uuid: string, picture: string},
     *         position: int,
     *         points: float,
     *         analytics: array{
     *             wins: int,
     *         },
     *     }>,
     * } $data
     */
    public static function withStandings(array $data): self
    {
        return new self($data);
    }
}
