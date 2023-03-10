<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateSeasonIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly FindSeasonGateway $findGateway,
        private readonly SaveSeasonGateway $saveGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateSeasonIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->findGateway->find($command->championshipId()->value(), $command->year()->value());

        if (null !== $id) {
            return $id;
        }

        $id     = new UuidValueObject($this->uuidGenerator->uuid4());
        $season = Season::create($id, $command->year(), $command->championshipId(), $command->ref());

        try {
            $this->saveGateway->save($season);
        } catch (Throwable $e) {
            throw new SeasonCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$season->pullDomainEvents());

        return $season->id();
    }
}
