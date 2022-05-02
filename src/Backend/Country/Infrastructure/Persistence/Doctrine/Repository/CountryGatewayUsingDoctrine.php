<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\Gateway\CountryGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CountryGatewayUsingDoctrine extends DoctrineRepository implements CountryGateway
{
    public function save(Country $country): void
    {
        parent::persist($country);
    }
}
