<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use RuntimeException;

abstract class FixtureSaver
{
    /** @param FixtureConverter[] $fixturesConverters */
    protected function __construct(
        private array $fixturesConverters,
    ) {
    }

    public function save(string $class, Fixture $fixture): void
    {
        foreach ($this->fixturesConverters as $fixturesConverter) {
            if (false === $fixturesConverter->handles($class)) {
                continue;
            }

            $entity = $fixturesConverter->convert($fixture);

            $this->saveAggregateRoot($entity);

            return;
        }

        throw new RuntimeException("Found no converter able to handle fixture of class {$class}.");
    }

    abstract protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void;
}
