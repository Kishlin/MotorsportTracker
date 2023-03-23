<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SessionGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\CreateRaceLapIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberQuery;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class ScrapRaceHistoryCommandHandler implements CommandHandler
{
    /** @var array<string, string> */
    private array $entryIdForCarNumberCache = [];

    public function __construct(
        private readonly RaceHistoryGateway $raceHistoryGateway,
        private readonly SessionGateway $sessionGateway,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ScrapRaceHistoryCommand $command): void
    {
        $session = $this->sessionGateway->find(
            $command->championship(),
            $command->year(),
            $command->event(),
            $command->sessionType(),
        );

        if (null === $session) {
            return;
        }

        $raceHistory = $this->raceHistoryGateway->fetch($session->ref())->data();

        foreach ($raceHistory['entries'] as $historyEntry) {
            $entryId = $this->findEntry($session, $historyEntry['carNumber']);

            $this->storeEntryForUuid($historyEntry['uuid'], $entryId);
        }

        foreach ($raceHistory['laps'] as $lap) {
            $lapKey = $lap['lap'];
            foreach ($lap['carPosition'] as $carPosition) {
                try {
                    $this->commandBus->execute(
                        CreateRaceLapIfNotExistsCommand::fromScalars(
                            $this->retrieveEntryForUuid($carPosition['entryUuid']),
                            $lapKey,
                            $carPosition['position'],
                            $carPosition['pit'],
                            $carPosition['time'],
                            $carPosition['gap']['timeToLead'],
                            $carPosition['gap']['lapsToLead'],
                            $carPosition['gap']['timeToNext'],
                            $carPosition['gap']['lapsToNext'],
                            $carPosition['tyreDetail'],
                        ),
                    );
                } catch (Throwable $e) {
                    $this->eventDispatcher->dispatch(RaceLapScrappingFailureEvent::forCarPosition($carPosition));
                }
            }
        }
    }

    private function findEntry(SessionDTO $session, string $carNumber): UuidValueObject
    {
        $findEntryResponse = $this->queryBus->ask(
            FindEntryForSessionAndNumberQuery::fromScalars($session->id(), (int) $carNumber),
        );

        assert($findEntryResponse instanceof FindEntryForSessionAndNumberResponse);

        return $findEntryResponse->id();
    }

    private function storeEntryForUuid(string $entryUuid, UuidValueObject $entry): void
    {
        $this->entryIdForCarNumberCache[$entryUuid] = $entry->value();
    }

    private function retrieveEntryForUuid(string $entryUuid): string
    {
        return $this->entryIdForCarNumberCache[$entryUuid];
    }
}
