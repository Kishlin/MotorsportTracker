<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Exception;
use InvalidArgumentException;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use RuntimeException;
use Symfony\Component\Yaml\Parser as YamlParser;

final class FixtureLoader
{
    /** @var array<string, array<string, array<string, bool|float|int|string>>> */
    private array $loadedFilesData = [];

    /** @var array<string, string> */
    private array $identifiers = [];

    private ?YamlParser $yamlParser = null;

    public function __construct(
        private readonly UuidGenerator $uuidGenerator,
        private readonly FixtureSaver $fixtureSaver,
        private readonly string $pathToFixtures,
    ) {
    }

    /**
     * @throws Exception
     */
    public function loadFixture(string $fixture): void
    {
        $this->loadFixtureIfNotLoaded($fixture);
    }

    public function reset(): void
    {
        $this->identifiers = [];
    }

    public function identifier(string $fixture): string
    {
        if (false === array_key_exists($fixture, $this->identifiers)) {
            throw new RuntimeException("Fixture {$fixture} appears to not have been loaded.");
        }

        return $this->identifiers[$fixture];
    }

    private function loadFixtureIfNotLoaded(string $fixture): void
    {
        if ($this->fixtureHasBeenLoaded($fixture)) {
            return;
        }

        if (1 !== preg_match('/^([\w]+)\.([\w]+)$/', $fixture, $matches)) {
            throw new InvalidArgumentException(sprintf('Fixture %s is not in the class.key format.', $fixture));
        }

        [, $fixtureClass, $fixtureName] = $matches;

        $this->parseFixtureFileIfNotLoaded($fixtureClass);

        $fixtureData = $this->loadedFilesData[$fixtureClass][$fixtureName];

        foreach ($fixtureData as $dataKey => $dataValue) {
            if (is_string($dataValue) && str_starts_with($dataValue, '@')) {
                $targetFixture = substr($dataValue, 1);
                $this->loadFixtureIfNotLoaded($targetFixture);
                $fixtureData[$dataKey] = $this->identifiers[$targetFixture];
            }
        }

        $identifier = $this->uuidGenerator->uuid4();

        $this->identifiers[$fixture] = $identifier;

        $this->fixtureSaver->save($fixtureClass, $identifier, $fixtureData);
    }

    private function fixtureHasBeenLoaded(string $fixture): bool
    {
        return array_key_exists($fixture, $this->identifiers);
    }

    private function parseFixtureFileIfNotLoaded(string $class): void
    {
        if (array_key_exists($class, $this->loadedFilesData)) {
            return;
        }

        /** @var array<string, array<string, bool|float|int|string>> $content */
        $content = $this->yamlParser()->parseFile("{$this->pathToFixtures}/{$class}.yaml");

        $this->loadedFilesData[$class] = $content;
    }

    private function yamlParser(): YamlParser
    {
        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        return $this->yamlParser;
    }
}
