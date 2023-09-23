<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared;

use Exception;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\EntityGateway;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\UniquenessCheckGateway;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;

final class EntityStoreSpy implements EntityGateway, UniquenessCheckGateway
{
    /** @var array<string, array<string, array<string, null|bool|float|int|string>>> */
    private array $store = [];

    /** @var array{saved: array<string, int>, updated: array<string, int>} */
    private array $actions = [
        'saved'   => [],
        'updated' => [],
    ];

    private UuidGenerator $uuidGenerator;

    public function __construct()
    {
        $this->uuidGenerator = new UuidGeneratorUsingRamsey();
    }

    public function resetState(): void
    {
        $this->actions = [
            'saved'   => [],
            'updated' => [],
        ];

        $this->store = [];
    }

    public function count(string $location): int
    {
        return count($this->store[$location] ?? []);
    }

    public function saved(string $location): int
    {
        return $this->actions['saved'][$location] ?? 0;
    }

    public function updated(string $location): int
    {
        return $this->actions['updated'][$location] ?? 0;
    }

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    public function add(string $location, array $data): string
    {
        $id = $this->uuidGenerator->uuid4();

        $this->store[$location][$id] = [
            'id' => $id,
            ...$data,
        ];

        return $id;
    }

    public function save(string $location, array $data): string
    {
        $this->actions['saved'][$location] = ($this->actions['saved'][$location] ?? 0) + 1;

        return $this->add($location, $data);
    }

    /**
     * @throws Exception
     */
    public function update(string $location, string $id, array $data): void
    {
        $this->actions['updated'][$location] = ($this->actions['updated'][$location] ?? 0) + 1;

        throw new Exception('Update Not implemented');
    }

    public function findIfExists(array $uniquenessConstraints, string $location, array $data): ?string
    {
        if (false === isset($this->store[$location])) {
            return null;
        }

        foreach ($this->store[$location] as $entity) {
            foreach ($uniquenessConstraints as $uniquenessConstraint) {
                $entityIsMatch = true;
                foreach ($uniquenessConstraint as $key) {
                    if ($entity[$key] !== $data[$key]) {
                        $entityIsMatch = false;

                        break;
                    }
                }

                if ($entityIsMatch) {
                    return (string) $entity['id'];
                }
            }
        }

        return null;
    }
}
