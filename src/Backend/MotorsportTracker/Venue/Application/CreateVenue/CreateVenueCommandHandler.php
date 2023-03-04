<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateVenueCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly VenueGateway $gateway,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateVenueCommand $command): UuidValueObject
    {
        $venueId = new UuidValueObject($this->uuidGenerator->uuid4());

        $venue = Venue::create($venueId, $command->name(), $command->slug(), $command->countryId());

        try {
            $this->gateway->save($venue);
        } catch (Throwable $e) {
            throw new VenueCreationFailureException(previous: $e);
        }

        return $venueId;
    }
}
