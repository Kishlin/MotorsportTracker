<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason;

use Kishlin\Backend\MotorsportStatsScrapper\Application\Traits\CountryCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\CreateTeamIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\CreateTeamPresentationIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ScrapTeamsForSeasonCommandHandler implements CommandHandler
{
    use CountryCreatorTrait;

    public function __construct(
        private readonly SeasonGateway $seasonGateway,
        private readonly ConstructorStandingsGateway $constructorStandingsGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(ScrapTeamsForSeasonCommand $command): void
    {
        $season = $this->seasonGateway->find($command->championshipName(), $command->year());
        if (null === $season) {
            return;
        }

        foreach ($this->constructorStandingsGateway->fetch($season->ref())->data()['standings'] as $standing) {
            try {
                $countryId = $this->createCountryIfNotExists($standing['countryRepresenting']);

                $teamId = $this->commandBus->execute(CreateTeamIfNotExistsCommand::fromScalars($standing['team']['uuid']));

                assert($teamId instanceof UuidValueObject);

                $this->commandBus->execute(
                    CreateTeamPresentationIfNotExistsCommand::fromScalars(
                        teamId: $teamId->value(),
                        seasonId: $season->id(),
                        countryId: $countryId->value(),
                        name: $standing['team']['name'],
                        color: $standing['team']['colour'],
                    ),
                );
            } catch (\Throwable) {
                $this->eventDispatcher->dispatch(TeamsForSeasonsScrappingFailureEvent::forStanding($standing));
            }
        }
    }
}