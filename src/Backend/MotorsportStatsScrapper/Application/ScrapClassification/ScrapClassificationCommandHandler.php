<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

use Kishlin\Backend\MotorsportStatsScrapper\Application\Traits\CountryCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Traits\DriverCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Application\Traits\TeamCreatorTrait;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SessionGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\CreateClassificationIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\CreateEntryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\CreateRetirementIfNotExistsCommand;
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
    private array $entryIdForDriverIdCache = [];

    public function __construct(
        private readonly ClassificationGateway $classificationGateway,
        private readonly SessionGateway $sessionGateway,
        private readonly CommandBus $commandBus,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ScrapClassificationCommand $command): void
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

        $classificationData = $this->classificationGateway->fetch($session->ref())->data();

        foreach ($classificationData['details'] as $details) {
            try {
                $teamId    = $this->createTeamIfNotExists($details['team']['uuid']);
                $countryId = $this->createCountryIfNotExists($details['nationality']);
                $this->createDriverIfNotExists($details['drivers'][0], $countryId);

                $entryId = $this->commandBus->execute(
                    CreateEntryIfNotExistsCommand::fromScalars($session->id(), $details['drivers'][0]['name'], $teamId->value(), (int) $details['carNumber']),
                );

                assert($entryId instanceof UuidValueObject);

                $this->storeEntryForDriver($details['drivers'][0], $entryId);

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
                    ),
                );
            } catch (Throwable) {
                $this->eventDispatcher->dispatch(ClassificationScrappingFailureEvent::forClassification($details));
            }
        }

        foreach ($classificationData['retirements'] as $retirement) {
            try {
                $this->commandBus->execute(
                    CreateRetirementIfNotExistsCommand::fromScalars(
                        $this->retrieveEntryForDriver($retirement['driver']),
                        $retirement['reason'],
                        $retirement['type'],
                        $retirement['dns'],
                        $retirement['lap'],
                    ),
                );
            } catch (Throwable) {
                $this->eventDispatcher->dispatch(RetirementScrappingFailureEvent::forRetirement($retirement));
            }
        }
    }

    /**
     * @param array{uuid: string} $driver
     */
    private function storeEntryForDriver(array $driver, UuidValueObject $entry): void
    {
        $this->entryIdForDriverIdCache[$driver['uuid']] = $entry->value();
    }

    /**
     * @param array{uuid: string} $driver
     */
    private function retrieveEntryForDriver(array $driver): string
    {
        return $this->entryIdForDriverIdCache[$driver['uuid']];
    }
}
