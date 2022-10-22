<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use RuntimeException;

abstract class FixtureSaver
{
    /** @var array<string, FixtureConverter> */
    private array $fixturesConverters = [];

    public function addConverter(string $key, FixtureConverter $converter): void
    {
        $this->fixturesConverters[$key] = $converter;
    }

    public function save(string $class, Fixture $fixture): void
    {
        if (false === array_key_exists($class, $this->fixturesConverters)) {
            throw new RuntimeException("No converter able to handle fixture of class {$class}.");
        }

        $entity = $this->fixturesConverters[$class]->convert($fixture);

        $this->saveAggregateRoot($entity);
    }

    abstract protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void;
}
