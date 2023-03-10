<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\EntryList;

use Kishlin\Backend\MotorsportTracker\EntryList\Application\CreateEntryIfNotExists\CreateEntryIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\EntryList\EntryRepositorySpy;

trait EntryListServicesTrait
{
    private ?EntryRepositorySpy $entryRepositorySpy = null;

    private ?CreateEntryIfNotExistsCommandHandler $createEntryIfNotExistsCommandHandler = null;

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
                $this->entryRepositorySpy(),
                $this->entryRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createEntryIfNotExistsCommandHandler;
    }
}
