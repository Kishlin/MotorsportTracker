<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Domain\SeasonGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\DTO\PossibleStandingCategory;
use Kishlin\Backend\MotorsportETL\Standing\Domain\DTO\PossibleStandingClass;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ObsoleteDataRemover;
use Kishlin\Backend\MotorsportETL\Standing\Domain\StandingType;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Result\FailResult;
use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\Result;

final readonly class ScrapStandingsCommandHandler implements CommandHandler
{
    public function __construct(
        private ObsoleteDataRemover $obsoleteDataRemover,
        private StandingsCachesInvalidator $standingsCachesInvalidator,
        private PossibleStandingsTransformer $possibleStandingsTransformer,
        private PossibleStandingsExtractor $possibleStandingsExtractor,
        private StandingsTransformer $standingsTransformer,
        private StandingsExtractor $standingsExtractor,
        private SeasonGateway $seasonGateway,
        private Loader $loader,
    ) {}

    public function __invoke(ScrapStandingsCommand $command): Result
    {
        $season = $this->seasonGateway->find($command->seasonFilter());

        if (null === $season) {
            return FailResult::withCode(ScrapStandingsFailures::SEASON_NOT_FOUND);
        }

        if ($command->cacheMustBeInvalidated()) {
            $this->standingsCachesInvalidator->invalidate($season);
        }

        $this->obsoleteDataRemover->removeObsoleteStandingsAndAnalytics($season);

        $possibleStandingsRaw = $this->possibleStandingsExtractor->extract($season);
        $possibleStandings    = $this->possibleStandingsTransformer->transform($possibleStandingsRaw);

        $this->scrapStandings($possibleStandings->constructorStandings(), StandingType::CONSTRUCTORS, $season);
        $this->scrapStandings($possibleStandings->driversStandings(), StandingType::DRIVERS, $season);
        $this->scrapStandings($possibleStandings->teamStandings(), StandingType::TEAMS, $season);

        return OkResult::create();
    }

    private function scrapStandings(
        PossibleStandingCategory $standings,
        StandingType $standingType,
        SeasonIdentity $season,
    ): void {
        if (false === $standings->isAvailable()) {
            return;
        }

        if (false === $standings->isMultiClass()) {
            $this->doScrapStandings($standingType, $season, null);

            return;
        }

        foreach ($standings->classes() as $class) {
            $this->doScrapStandings($standingType, $season, $class);
        }
    }

    private function doScrapStandings(
        StandingType $standingType,
        SeasonIdentity $season,
        ?PossibleStandingClass $class,
    ): void {
        $standingsRaw = $this->standingsExtractor->extract($season, $standingType, $class);
        $standings    = $this->standingsTransformer->transform($standingsRaw, $standingType, $season, $class);

        foreach ($standings as $standing) {
            $this->loader->load($standing);
        }
    }
}
