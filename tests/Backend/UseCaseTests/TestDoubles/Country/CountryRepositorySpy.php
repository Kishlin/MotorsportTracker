<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country;

use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CountryIdForCodeGateway;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\Gateway\CountryGateway;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Country[] $objects
 *
 * @method Country[]    all()
 * @method null|Country get(UuidValueObject $id)
 * @method Country      safeGet(UuidValueObject $id)
 */
final class CountryRepositorySpy extends AbstractRepositorySpy implements CountryGateway, CountryIdForCodeGateway
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

    public function idForCode(CountryCode $code): ?CountryId
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
