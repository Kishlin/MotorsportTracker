<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Championship;

use Behat\Step\Given;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class SeasonContext extends BackofficeContext
{
    #[Given('the season :name exists')]
    public function theEventSessionExists(string $name): void
    {
        self::database()->loadFixture("motorsport.championship.season.{$this->format($name)}");
    }
}
