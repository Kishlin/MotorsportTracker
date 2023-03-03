<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country;

use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\SaveCountryGateway;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\SearchCountryGateway;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Country[] $objects
 *
 * @method Country[]    all()
 * @method null|Country get(UuidValueObject $id)
 * @method Country      safeGet(UuidValueObject $id)
 */
final class SaveSearchCountryRepositorySpy extends AbstractRepositorySpy implements SaveCountryGateway, SearchCountryGateway
{
    /**
     * @throws Exception
     */
    public function save(Country $country): void
    {
        if ($this->codeAlreadyTaken($country)) {
            throw new Exception();
        }

        $this->objects[$country->id()->value()] = $country;
    }

    public function searchForCode(StringValueObject $code): ?UuidValueObject
    {
        foreach ($this->objects as $savedCountry) {
            if ($savedCountry->code()->equals($code)) {
                return $savedCountry->id();
            }
        }

        return null;
    }

    private function codeAlreadyTaken(Country $country): bool
    {
        foreach ($this->objects as $savedCountry) {
            if ($savedCountry->code()->equals($country->code())) {
                return true;
            }
        }

        return false;
    }
}
