<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Domain\Gateway;

use Kishlin\Backend\Country\Domain\Entity\Country;

interface CountryGateway
{
    public function save(Country $country): void;
}
