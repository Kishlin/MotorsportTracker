<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Country\Infrastructure\Persistance\Repository;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Infrastructure\Persistence\Repository\SaveCountryRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Infrastructure\Persistence\Repository\SaveCountryRepository
 */
final class SaveCountryRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveACountry(): void
    {
        $country = Country::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('fr'),
            new StringValueObject('France'),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveCountryRepository(self::connection());

        $repository->save($country);

        self::assertAggregateRootWasSaved($country);
    }
}
