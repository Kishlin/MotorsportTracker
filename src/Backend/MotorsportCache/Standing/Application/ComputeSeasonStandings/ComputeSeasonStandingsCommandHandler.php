<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Event\PreviousStandingsDeletedEvent;
use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Event\StandingsCreationFailedEvent;
use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway\DeleteStandingsIfExistsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway\SaveStandingsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway\StandingsDataGateway;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\Standings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\ConstructorStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\TeamStandingsView;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Throwable;

final readonly class ComputeSeasonStandingsCommandHandler implements CommandHandler
{
    public function __construct(
        private DeleteStandingsIfExistsGateway $deleteExistingGateway,
        private SaveStandingsGateway $saveStandingsViewGateway,
        private StandingsDataGateway $standingsDataGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(ComputeSeasonStandingsCommand $command): ?UuidValueObject
    {
        $standings = $this->standingsDataGateway->findForSeason($command->championshipName(), $command->year());

        $championshipSlug = StringHelper::slugify($command->championshipName());

        $standingsView = Standings::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            new StringValueObject($championshipSlug),
            new IntValueObject($command->year()),
            ConstructorStandingsView::with($standings->constructorStandings()),
            TeamStandingsView::with($standings->teamStandings()),
            DriverStandingsView::with($standings->driverStandings()),
        );

        $this->deletePreviousStandings($championshipSlug, $command->year());

        try {
            $this->saveStandingsViewGateway->save($standingsView);
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(
                StandingsCreationFailedEvent::forSeason($command->championshipName(), $command->year(), $e),
            );

            return null;
        }

        $this->eventDispatcher->dispatch(...$standingsView->pullDomainEvents());

        return $standingsView->id();
    }

    private function deletePreviousStandings(string $championshipSlug, int $year): void
    {
        $itDeletedSomething = $this->deleteExistingGateway->deleteIfExists($championshipSlug, $year);

        if ($itDeletedSomething) {
            $this->eventDispatcher->dispatch(
                PreviousStandingsDeletedEvent::forSeason($championshipSlug, $year),
            );
        }
    }
}
