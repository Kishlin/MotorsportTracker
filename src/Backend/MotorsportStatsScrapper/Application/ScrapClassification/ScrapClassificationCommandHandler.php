<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\CountryCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\DriverCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits\TeamCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Event\NoSessionsFoundEvent;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SessionsListGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\CreateClassificationIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\CreateEntryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\CreateRetirementIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver\RegisterAdditionalDriverCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class ScrapClassificationCommandHandler implements CommandHandler
{
    use CountryCreatorTrait;
    use DriverCreatorTrait;
    use TeamCreatorTrait;

    /** @var array<string, string> */
    private array $entryIdForDriverIdCache;

    public function __construct(
        private readonly SessionsListGateway $sessionsGateway,
        private readonly ClassificationGateway $classificationGateway,
        private readonly CommandBus $commandBus,
        private readonly EventDispatcher $eventDispatcher,
    ) {
        $this->entryIdForDriverIdCache = [];
    }

    public function __invoke(ScrapClassificationCommand $command): void
    {
        $sessions = $this->sessionsGateway->allSessions($command->championship(), $command->year(), $command->event());

        if (empty($sessions->list())) {
            $this->eventDispatcher->dispatch(NoSessionsFoundEvent::forScrapClassificationCommand($command));

            return;
        }

        $eventScrapped = [];

        foreach ($sessions->list() as $session) {
            $this->scrapClassificationsForSession($session);

            if (false === array_key_exists($session->event(), $eventScrapped)) {
                $eventScrapped[$session->event()] = true;
            }
        }

        foreach ($eventScrapped as $event => $boolean) {
            $this->eventDispatcher->dispatch(ClassificationScrappingSuccessEvent::forEvent($event));
        }
    }

    private function scrapClassificationsForSession(SessionDTO $session): void
    {
        $classificationData = $this->classificationGateway->fetch($session->ref())->data();

        foreach ($classificationData['details'] as $details) {
            try {
                $countryId = null !== $details['nationality']
                    ? $this->createCountryIfNotExists($details['nationality'])->value()
                    : null;

                $team = $this->createTeamIfNotExists(
                    $session->season(),
                    $details['team']['name'],
                    $details['team']['colour'],
                    $details['team']['uuid'],
                );

                $this->createDriverIfNotExists($details['drivers'][0], $countryId);

                $entryId = $this->commandBus->execute(
                    CreateEntryIfNotExistsCommand::fromScalars(
                        $session->id(),
                        $details['drivers'][0]['name'],
                        $team->value(),
                        (int) $details['carNumber'],
                    ),
                );

                assert($entryId instanceof UuidValueObject);

                for ($i = 1, $max = count($details['drivers']); $i < $max; ++$i) {
                    $additionalDriver = $this->createDriverIfNotExists($details['drivers'][$i]);

                    $this->commandBus->execute(
                        RegisterAdditionalDriverCommand::fromScalars($entryId->value(), $additionalDriver->value()),
                    );
                }

                $this->storeEntryForCarNumber($details['carNumber'], $entryId);

                $this->commandBus->execute(
                    CreateClassificationIfNotExistsCommand::fromScalars(
                        $entryId->value(),
                        $details['finishPosition'],
                        $details['gridPosition'],
                        $details['laps'],
                        $details['points'],
                        $details['time'],
                        $details['classifiedStatus'],
                        $details['avgLapSpeed'],
                        $details['fastestLapTime'],
                        $details['gap']['timeToLead'],
                        $details['gap']['timeToNext'],
                        $details['gap']['lapsToLead'],
                        $details['gap']['lapsToNext'],
                        $details['best']['lap'],
                        $details['best']['time'],
                        $details['best']['fastest'],
                        $details['best']['speed'],
                    ),
                );
            } catch (Throwable $e) {
                $this->eventDispatcher->dispatch(ClassificationScrappingFailureEvent::forClassification($details, $e));
            }
        }

        foreach ($classificationData['retirements'] as $retirement) {
            try {
                $this->commandBus->execute(
                    CreateRetirementIfNotExistsCommand::fromScalars(
                        $this->retrieveEntryForCarNumber($retirement['carNumber']),
                        $retirement['reason'],
                        $retirement['type'],
                        $retirement['dns'],
                        $retirement['lap'],
                    ),
                );
            } catch (Throwable $e) {
                $this->eventDispatcher->dispatch(RetirementScrappingFailureEvent::forRetirement($retirement, $e));
            }
        }
    }

    private function storeEntryForCarNumber(string $carNumber, UuidValueObject $entry): void
    {
        $this->entryIdForDriverIdCache[$carNumber] = $entry->value();
    }

    private function retrieveEntryForCarNumber(string $carNumber): string
    {
        return $this->entryIdForDriverIdCache[$carNumber];
    }
}
