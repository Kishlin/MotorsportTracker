<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\Shared;

use Behat\Step\Given;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class FixturesContext extends BackofficeContext
{
    #[Given('the :fixture :name does not exist yet')]
    public function theFixtureDoesNotExistYet(): void {}
}
