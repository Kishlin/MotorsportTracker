<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists\CreateEntryIfNotExistsCommandHandler;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\EntryRepositorySpy;

trait EntryServicesTrait
{
    private ?EntryRepositorySpy $entryRepositorySpy = null;

    private ?CreateEntryIfNotExistsCommandHandler $createEntryIfNotExistsCommandHandler = null;

    private ?FindEntryForSessionAndNumberQueryHandler $findEntryForSessionAndNumberQueryHandler = null;

    public function entryRepositorySpy(): EntryRepositorySpy
    {
        if (null === $this->entryRepositorySpy) {
            $this->entryRepositorySpy = new EntryRepositorySpy();
        }

        return $this->entryRepositorySpy;
    }

    public function createEntryIfNotExistsCommandHandler(): CreateEntryIfNotExistsCommandHandler
    {
        if (null === $this->createEntryIfNotExistsCommandHandler) {
            $this->createEntryIfNotExistsCommandHandler = new CreateEntryIfNotExistsCommandHandler(
                $this->driverRepositorySpy(),
                $this->entryRepositorySpy(),
                $this->entryRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createEntryIfNotExistsCommandHandler;
    }

    public function findEntryForSessionAndNumberQueryHandler(): FindEntryForSessionAndNumberQueryHandler
    {
        if (null === $this->findEntryForSessionAndNumberQueryHandler) {
            $this->findEntryForSessionAndNumberQueryHandler = new FindEntryForSessionAndNumberQueryHandler(
                $this->entryRepositorySpy(),
            );
        }

        return $this->findEntryForSessionAndNumberQueryHandler;
    }
}