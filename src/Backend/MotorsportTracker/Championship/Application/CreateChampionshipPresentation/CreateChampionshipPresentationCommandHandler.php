<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SearchChampionshipGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateChampionshipPresentationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveChampionshipPresentationGateway $saveGateway,
        private readonly SearchChampionshipGateway $searchGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $dispatcher,
        private readonly Clock $clock,
    ) {
    }

    public function __invoke(CreateChampionshipPresentationCommand $command): UuidValueObject
    {
        $championshipId = $this->searchGateway->findIfExists($command->championship(), new NullableUuidValueObject(null));
        if (null === $championshipId) {
            throw new ChampionshipNotFoundDomainException();
        }

        $id        = new UuidValueObject($this->uuidGenerator->uuid4());
        $createdOn = new DateTimeValueObject($this->clock->now());

        $championshipPresentation = ChampionshipPresentation::create(
            $id,
            $championshipId,
            $command->icon(),
            $command->color(),
            $createdOn,
        );

        $this->saveGateway->save($championshipPresentation);

        $this->dispatcher->dispatch(...$championshipPresentation->pullDomainEvents());

        return $id;
    }
}
