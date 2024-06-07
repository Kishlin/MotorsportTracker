<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\Persistence;

use Kishlin\Tests\Apps\MotorsportTracker\Backend\ApiTests\Context\BackendApiContext;

final class FixtureContext extends BackendApiContext
{
    /**
     * @Given the :class :name does not exist yet
     */
    public function theFixtureDoesNotExistYet(string $class, string $name): void {}
}
