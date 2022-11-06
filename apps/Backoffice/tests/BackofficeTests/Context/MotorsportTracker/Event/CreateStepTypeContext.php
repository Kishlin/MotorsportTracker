<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Event;

use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class CreateStepTypeContext extends BackofficeContext
{
    /**
     * @Given the stepType :label exists
     */
    public function theStepTypeExists(string $label): void
    {
        self::database()->loadFixture("motorsport.event.stepType.{$this->format($label)}");
    }
}
