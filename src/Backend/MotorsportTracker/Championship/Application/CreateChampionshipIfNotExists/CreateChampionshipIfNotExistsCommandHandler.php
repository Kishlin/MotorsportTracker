<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateChampionshipIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchChampionshipGateway $searchGateway,
        private readonly SaveChampionshipGateway $saveGateway,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateChampionshipIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findBySlug($command->slug()->value());

        if (null !== $id) {
            return $id;
        }

        $id = new UuidValueObject($this->uuidGenerator->uuid4());

        $championship = Championship::create($id, $command->name(), $command->slug());

        try {
            $this->saveGateway->save($championship);
        } catch (Throwable $e) {
            throw new ChampionshipCreationFailureException(previous: $e);
        }

        return $championship->id();
    }
}
