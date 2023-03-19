<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class TeamsForSeasonsScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     constructor: array{name: string, uuid: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     countryRepresenting: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         wins: int,
     *     },
     * } $standing
     */
    private function __construct(
        private readonly array $standing,
    ) {
    }

    /**
     * @return array{
     *     constructor: array{name: string, uuid: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     countryRepresenting: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         wins: int,
     *     },
     * } $standing
     */
    public function standing(): array
    {
        return $this->standing;
    }

    /**
     * @param array{
     *     constructor: array{name: string, uuid: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     countryRepresenting: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         wins: int,
     *     },
     * } $standing
     */
    public static function forStanding(array $standing): self
    {
        return new self($standing);
    }
}
