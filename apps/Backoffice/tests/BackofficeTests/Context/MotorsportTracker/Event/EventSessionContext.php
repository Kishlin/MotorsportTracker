<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Event;

use Behat\Step\Given;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class EventSessionContext extends BackofficeContext
{
    #[Given('the eventSession :name exists')]
    public function theEventSessionExists(string $name): void
    {
        self::database()->loadFixture("motorsport.event.eventSession.{$this->format($name)}");
    }
}
