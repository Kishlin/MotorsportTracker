<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\MotorsportTracker\Result;

use Behat\Step\Given;
use Kishlin\Tests\Apps\Backoffice\BackofficeTests\Context\BackofficeContext;

final class ClassificationContext extends BackofficeContext
{
    #[Given('the classification :name exists')]
    public function theClassificationExists(string $name): void
    {
        self::database()->loadFixture("motorsport.result.classification.{$this->format($name)}");
    }
}
