<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateTeamPresentationIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchTeamPresentationGateway $searchGateway,
        private readonly SaveTeamPresentationGateway $saveGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateTeamPresentationIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findByTeamAndSeason($command->teamId(), $command->seasonId());

        if (null !== $id) {
            return $id;
        }

        $id = new UuidValueObject($this->uuidGenerator->uuid4());

        $presentation = TeamPresentation::create(
            $id,
            $command->teamId(),
            $command->seasonId(),
            $command->countryId(),
            $command->name(),
            $command->color(),
        );

        try {
            $this->saveGateway->save($presentation);
        } catch (Throwable $e) {
            throw new TeamPresentationCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$presentation->pullDomainEvents());

        return $presentation->id();
    }
}
