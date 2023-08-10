<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Event\NoSessionsFoundEvent;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SessionsListGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\CreateRaceLapIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\EntryNotFoundException;
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
        private readonly SessionsListGateway $sessionsGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(ScrapRaceHistoryCommand $command): void
    {
        $sessions = $this->sessionsGateway->allSessions($command->championship(), $command->year(), $command->event());

        if (empty($sessions->list())) {
            $this->eventDispatcher->dispatch(NoSessionsFoundEvent::forScrapRaceHistoryCommand($command));

            return;
        }

        $eventScrapped = [];

        foreach ($sessions->list() as $session) {
            $this->scrapRaceLapsForSession($session);

            if (false === array_key_exists($session->event(), $eventScrapped)) {
                $eventScrapped[$session->event()] = true;
            }
        }

        foreach ($eventScrapped as $event => $boolean) {
            $this->eventDispatcher->dispatch(RaceLapScrappingSuccessEvent::forEvent($event));
        }
    }

    private function scrapRaceLapsForSession(SessionDTO $session): void
    {
        $raceHistory = $this->raceHistoryGateway->fetch($session->ref())->data();

        if (empty($raceHistory['laps'])) {
            return;
        }

        $skippedEntries = [];

        foreach ($raceHistory['entries'] as $historyEntry) {
            try {
                $entryId = $this->findEntry($session, $historyEntry['carNumber']);
            } catch (EntryNotFoundException) {
                $skippedEntries[$historyEntry['uuid']] = $historyEntry['carNumber'];

                $this->eventDispatcher->dispatch(
                    EntryNotFoundEvent::fromScalars($session->id(), $historyEntry['carNumber']),
                );

                continue;
            }

            $this->storeEntryForUuid($historyEntry['uuid'], $entryId);
        }

        foreach ($raceHistory['laps'] as $lap) {
            $lapKey = $lap['lap'];
            foreach ($lap['carPosition'] as $carPosition) {
                $entry = $carPosition['entryUuid'];

                if (array_key_exists($entry, $skippedEntries)) {
                    $this->eventDispatcher->dispatch(
                        RaceLapForSkippedEntryEvent::fromScalars($session->id(), $skippedEntries[$entry], $carPosition),
                    );

                    continue;
                }

                try {
                    $this->commandBus->execute(
                        CreateRaceLapIfNotExistsCommand::fromScalars(
                            $this->retrieveEntryForUuid($entry),
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
                    $this->eventDispatcher->dispatch(RaceLapScrappingFailureEvent::forCarPosition($carPosition, $e));
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
