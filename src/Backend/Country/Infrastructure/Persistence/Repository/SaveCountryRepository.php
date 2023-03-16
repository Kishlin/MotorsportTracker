<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Repository;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\SaveCountryGateway;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveCountryRepository extends CoreRepository implements SaveCountryGateway
{
    public function save(Country $country): void
    {
        parent::persist($country);
    }
}
