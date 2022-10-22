<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

interface FixtureConverter
{
    public function handles(string $class): bool;

    public function convert(Fixture $fixture): AggregateRoot;
}
