<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Country;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\CountryRepositorySpy;

trait CountryServicesTrait
{
    private ?CountryRepositorySpy $countryRepositorySpy = null;

    private ?CreateCountryIfNotExistsCommandHandler $createCountryIfNotExistsCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function countryRepositorySpy(): CountryRepositorySpy
    {
        if (null === $this->countryRepositorySpy) {
            $this->countryRepositorySpy = new CountryRepositorySpy();
        }

        return $this->countryRepositorySpy;
    }

    public function createCountryIfNotExistsCommandHandler(): CreateCountryIfNotExistsCommandHandler
    {
        if (null === $this->createCountryIfNotExistsCommandHandler) {
            $countryRepositorySpy = $this->countryRepositorySpy();

            $this->createCountryIfNotExistsCommandHandler = new CreateCountryIfNotExistsCommandHandler(
                $countryRepositorySpy,
                $this->eventDispatcher(),
                $countryRepositorySpy,
                $this->uuidGenerator(),
            );
        }

        return $this->createCountryIfNotExistsCommandHandler;
    }
}
