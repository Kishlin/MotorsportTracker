<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Shared;

use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\ObjectStoreSpy;

trait PersistenceServicesTrait
{
    private ?FixtureLoader $coreFixtureLoader = null;

    private ?ObjectStoreSpy $objectStoreSpy = null;

    abstract public function uuidGenerator(): UuidGenerator;

    public function objectStoreSpy(): ObjectStoreSpy
    {
        if (null === $this->objectStoreSpy) {
            $this->objectStoreSpy = new ObjectStoreSpy();
        }

        return $this->objectStoreSpy;
    }

    public function coreFixtureLoader(): FixtureLoader
    {
        if (null === $this->coreFixtureLoader) {
            $this->coreFixtureLoader = new FixtureLoader(
                $this->uuidGenerator(),
                $this->objectStoreSpy(),
                '/app/etc/Fixtures/Core',
            );
        }

        return $this->coreFixtureLoader;
    }
}
