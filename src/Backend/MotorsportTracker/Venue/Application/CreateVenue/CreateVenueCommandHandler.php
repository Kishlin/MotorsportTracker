<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Gateway\VenueGateway;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateVenueCommandHandler implements CommandHandler
{
    public function __construct(
        private VenueGateway $gateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateVenueCommand $command): VenueId
    {
        $venueId = new VenueId($this->uuidGenerator->uuid4());

        $venue = Venue::create($venueId, $command->name(), $command->countryId());

        try {
            $this->gateway->save($venue);
        } catch (Throwable $e) {
            throw new VenueCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$venue->pullDomainEvents());

        return $venueId;
    }
}
