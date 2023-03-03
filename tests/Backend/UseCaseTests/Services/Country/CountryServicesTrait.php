<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Country;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;

trait CountryServicesTrait
{
    private ?SaveSearchCountryRepositorySpy $countryRepositorySpy = null;

    private ?CreateCountryIfNotExistsCommandHandler $createCountryIfNotExistsCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function countryRepositorySpy(): SaveSearchCountryRepositorySpy
    {
        if (null === $this->countryRepositorySpy) {
            $this->countryRepositorySpy = new SaveSearchCountryRepositorySpy();
        }

        return $this->countryRepositorySpy;
    }

    public function createCountryIfNotExistsCommandHandler(): CreateCountryIfNotExistsCommandHandler
    {
        if (null === $this->createCountryIfNotExistsCommandHandler) {
            $countryRepositorySpy = $this->countryRepositorySpy();

            $this->createCountryIfNotExistsCommandHandler = new CreateCountryIfNotExistsCommandHandler(
                $countryRepositorySpy,
                $countryRepositorySpy,
                $this->uuidGenerator(),
            );
        }

        return $this->createCountryIfNotExistsCommandHandler;
    }
}
