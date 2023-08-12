<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\EntryAdditionalDriver;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class RegisterAdditionalDriverCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchEntryAdditionalDriverGateway $searchGateway,
        private SaveEntryAdditionalDriverGateway $saveGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(RegisterAdditionalDriverCommand $command): UuidValueObject
    {
        $existing = $this->searchGateway->find($command->entry(), $command->driver());
        if (null !== $existing) {
            return $existing;
        }

        $entryAdditionalDriver = EntryAdditionalDriver::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->entry(),
            $command->driver(),
        );

        $this->saveGateway->saveEntryAdditionalDriver($entryAdditionalDriver);

        $this->eventDispatcher->dispatch(...$entryAdditionalDriver->pullDomainEvents());

        return $entryAdditionalDriver->id();
    }
}
