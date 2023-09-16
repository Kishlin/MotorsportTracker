<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SearchChampionshipGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateChampionshipIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchChampionshipGateway $searchGateway,
        private SaveChampionshipGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateChampionshipIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findIfExists($command->name(), $command->ref());

        if (null !== $id) {
            return $id;
        }

        $id = new UuidValueObject($this->uuidGenerator->uuid4());

        $championship = Championship::create($id, $command->name(), $command->shortName(), $command->shortCode(), $command->ref());

        try {
            $this->saveGateway->save($championship);
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(ChampionshipCreationFailureEvent::forCommand($command, $e));
        }

        $this->eventDispatcher->dispatch(...$championship->pullDomainEvents());

        return $championship->id();
    }
}
