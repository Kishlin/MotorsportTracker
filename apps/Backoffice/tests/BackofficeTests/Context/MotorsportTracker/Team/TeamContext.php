<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Team;

use Behat\Step\Given;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class TeamContext extends BackofficeContext
{
    #[Given('the :name team exists')]
    public function theTeamExists(string $name): void
    {
        self::database()->loadFixture("motorsport.team.team.{$this->format($name)}");
    }
}
