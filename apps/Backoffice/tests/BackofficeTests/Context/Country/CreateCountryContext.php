<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\Country;

use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class CreateCountryContext extends BackofficeContext
{
    /**
     * @Given the country :name exists
     */
    public function theCountryExists(string $name): void
    {
        self::database()->loadFixture("country.country.{$this->format($name)}");
    }
}
