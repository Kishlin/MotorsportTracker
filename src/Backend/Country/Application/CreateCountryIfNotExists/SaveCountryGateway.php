<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\CreateCountryIfNotExists;

use Kishlin\Backend\Country\Domain\Entity\Country;

interface SaveCountryGateway
{
    public function save(Country $country): void;
}
