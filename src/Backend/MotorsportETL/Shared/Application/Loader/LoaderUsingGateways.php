<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application\Loader;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;
use Kishlin\Backend\Shared\Domain\Entity\Mapped;
use Kishlin\Backend\Shared\Domain\Tools;
use LogicException;
use Psr\Log\LoggerInterface;
use ReflectionException;

final readonly class LoaderUsingGateways implements Loader
{
    public function __construct(
        private UniquenessCheckGateway $uniquenessCheckGateway,
        private EntityGateway $writeGateway,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function load(Entity $entity): void
    {
        $this->doLoad($entity);
    }

    public function doLoad(Entity $entity): string
    {
        $location = $this->computeLocation($entity);
        $data     = $this->computeData($entity->mappedData());

        if (false === $entity instanceof GuardedAgainstDoubles) {
            $this->logger?->info("Saving {$location} without uniqueness check", $data);

            return $this->writeGateway->save($location, $data);
        }

        $existingId = $this->uniquenessCheckGateway->findIfExists(
            $entity->uniquenessConstraints(),
            $location,
            $data,
        );

        if (null === $existingId) {
            $this->logger?->info("Saving non-existing {$location}", $data);

            return $this->writeGateway->save($location, $data);
        }

        $strategy = $entity->strategyOnDuplicate();

        if (DuplicateStrategy::SKIP === $strategy) {
            $this->logger?->debug("Skipping existing {$location}");

            return $existingId;
        }

        if (DuplicateStrategy::UPDATE === $strategy) {
            $this->logger?->info("Updating existing {$location}", $data);

            $this->writeGateway->update($location, $existingId, $data);

            return $existingId;
        }

        // @phpstan-ignore-next-line
        throw new LogicException("Do not know what to do of duplicate {$location} with id {$existingId}");
    }

    private function computeLocation(Entity $entity): string
    {
        try {
            return Tools::fromPascalToSnakeCase(Tools::shortClassName($entity));
        } catch (ReflectionException) {
            return $entity::class;
        }
    }

    /**
     * @param array<string, null|bool|DateTimeImmutable|Entity|float|int|Mapped|string> $mappedDetails
     *
     * @return array<string, null|bool|float|int|string>
     */
    private function computeData(array $mappedDetails): array
    {
        $data = [];

        foreach ($mappedDetails as $key => $value) {
            if ($value instanceof Entity) {
                $nestedId   = $this->doLoad($value);
                $data[$key] = $nestedId;

                continue;
            }

            if ($value instanceof Mapped) {
                $data = array_merge($data, $this->computeData($value->mappedData()));

                continue;
            }

            if ($value instanceof DateTimeImmutable) {
                $data[$key] = $value->format('Y-m-d H:i:s');

                continue;
            }

            if (is_bool($value)) {
                $data[$key] = (int) $value;

                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }
}
