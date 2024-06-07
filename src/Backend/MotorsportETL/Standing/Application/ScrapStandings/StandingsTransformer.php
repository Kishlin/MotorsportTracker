<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Generator;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\DTO\PossibleStandingClass;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\AnalyticsConstructors;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\AnalyticsTeams;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\ConstructorTeam;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\StandingConstructor;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\StandingDriver;
use Kishlin\Backend\MotorsportETL\Standing\Domain\Entity\StandingTeam;
use Kishlin\Backend\MotorsportETL\Standing\Domain\StandingType;
use Kishlin\Backend\Shared\Domain\Entity\Entity;

final readonly class StandingsTransformer
{
    public function __construct(
        private JsonableStringTransformer $jsonableStringParser,
    ) {}

    /**
     * @return Generator<Entity>
     */
    public function transform(
        mixed $extractorResponse,
        StandingType $standingType,
        SeasonIdentity $season,
        ?PossibleStandingClass $class,
    ): Generator {
        if (StandingType::CONSTRUCTORS === $standingType) {
            yield from $this->transformConstructorStandings($extractorResponse, $season, $class);

            return;
        }

        if (StandingType::DRIVERS === $standingType) {
            yield from $this->transformDriverStandings($extractorResponse, $season, $class);

            return;
        }

        if (StandingType::TEAMS === $standingType) {
            yield from $this->transformTeamStandings($extractorResponse, $season, $class);
        }
    }

    /**
     * @return Generator<Entity>
     */
    public function transformConstructorStandings(
        mixed $extractorResponse,
        SeasonIdentity $season,
        ?PossibleStandingClass $class,
    ): Generator {
        /**
         * @var array{
         *     standings: array<int, array{
         *         constructor: array{name: string, uuid: string},
         *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string}|null,
         *         countryRepresenting: array{name: string, uuid: string, picture: string}|null,
         *         position: int,
         *         points: float,
         *         analytics: array{wins: int},
         *     }>
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($content['standings'] as $standing) {
            yield AnalyticsConstructors::fromData($season->id(), $standing);

            yield StandingConstructor::fromData($season->id(), $class?->name(), $standing);

            if (null !== $standing['team']) {
                yield ConstructorTeam::fromData($season->id(), $standing);
            }
        }
    }

    /**
     * @return Generator<Entity>
     */
    public function transformDriverStandings(
        mixed $extractorResponse,
        SeasonIdentity $season,
        ?PossibleStandingClass $class,
    ): Generator {
        /**
         * @var array{
         *     standings: array<int, array{
         *         driver: array{name: string, shortCode: string, uuid: string},
         *         nationality: array{name: string, uuid: string, picture: string},
         *         team: array{name: string, uuid: string},
         *         position: int,
         *         points: float,
         *         analytics: array{
         *             avgFinishPosition: float,
         *             classWins: int,
         *             fastestLaps: int,
         *             finalAppearances: int,
         *             hatTricks: int,
         *             podiums: int,
         *             poles: int,
         *             racesLed: int,
         *             ralliesLed: int,
         *             retirements: int,
         *             semiFinalAppearances: int,
         *             stageWins: int,
         *             starts: int,
         *             top10s: int,
         *             top5s: int,
         *             wins: int,
         *             winsPercentage: float,
         *         },
         *     }>
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($content['standings'] as $standing) {
            yield AnalyticsDrivers::fromData($season->id(), $standing);

            yield StandingDriver::fromData($season->id(), $class?->name(), $standing);
        }
    }

    /**
     * @return Generator<Entity>
     */
    public function transformTeamStandings(
        mixed $extractorResponse,
        SeasonIdentity $season,
        ?PossibleStandingClass $class,
    ): Generator {
        /**
         * @var array{
         *     standings: array<int, array{
         *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
         *         icon: string,
         *         countryRepresenting: array{name: string, uuid: string, picture: string},
         *         position: int,
         *         points: float,
         *         analytics: array{
         *             classWins: int,
         *             fastestLaps: int,
         *             finalAppearances: int,
         *             finishes1And2: int,
         *             podiums: int,
         *             poles: int,
         *             qualifies1And2: int,
         *             racesLed: int,
         *             ralliesLed: int,
         *             retirements: int,
         *             semiFinalAppearances: int,
         *             stageWins: int,
         *             starts: int,
         *             top10s: int,
         *             top5s: int,
         *             wins: int,
         *             winsPercentage: float,
         *         },
         *     }>
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($content['standings'] as $standing) {
            yield AnalyticsTeams::fromData($season->id(), $standing);

            yield StandingTeam::fromData($season->id(), $class?->name(), $standing);
        }
    }
}
