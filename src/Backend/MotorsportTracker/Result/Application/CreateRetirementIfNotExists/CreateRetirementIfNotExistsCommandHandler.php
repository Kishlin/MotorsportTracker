<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateRetirementIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchRetirementGateway $searchGateway,
        private readonly SaveRetirementGateway $saveGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateRetirementIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->findForEntry($command->entry());
        if (null !== $id) {
            return $id;
        }

        $retirement = Retirement::create(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            $command->entry(),
            $command->reason(),
            $command->type(),
            $command->dns(),
            $command->lap(),
        );

        try {
            $this->saveGateway->save($retirement);
        } catch (Throwable) {
            throw new RetirementCreationFailureException();
        }

        $this->eventDispatcher->dispatch(...$retirement->pullDomainEvents());

        return $retirement->id();
    }
}
