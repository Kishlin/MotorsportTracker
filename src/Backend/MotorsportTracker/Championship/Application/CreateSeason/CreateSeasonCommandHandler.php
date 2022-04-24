<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateSeasonCommandHandler implements CommandHandler
{
    public function __construct(
        private SeasonGateway $gateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateSeasonCommand $command): SeasonId
    {
        $id = new SeasonId($this->uuidGenerator->uuid4());

        $season = Season::create($id, $command->year(), $command->championshipId());

        try {
            $this->gateway->save($season);
        } catch (Throwable $e) {
            throw new SeasonCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$season->pullDomainEvents());

        return $season->id();
    }
}
