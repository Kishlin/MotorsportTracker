<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Team;

use Behat\Step\Given;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class TeamPresentationContext extends BackofficeContext
{
    #[Given('the team presentation :name exists')]
    public function theTeamExists(string $name): void
    {
        self::database()->loadFixture("motorsport.team.teamPresentation.{$this->format($name)}");
    }
}
