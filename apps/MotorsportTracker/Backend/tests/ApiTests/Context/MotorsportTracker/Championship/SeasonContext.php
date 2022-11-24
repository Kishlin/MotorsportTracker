<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\MotorsportTracker\Championship;

use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;

final class SeasonContext extends BackendApiContext
{
    /**
     * @Given the season :season exists
     */
    public function theSeasonExists(string $season): void
    {
        self::database()->loadFixture("motorsport.championship.season.{$this->format($season)}");
    }
}
