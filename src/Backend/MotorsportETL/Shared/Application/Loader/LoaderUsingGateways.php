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
use Throwable;

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

    public function doLoad(Entity $entity): ?string
    {
        $location = $this->computeLocation($entity);
        $data     = $this->computeData($entity->mappedData());

        if (false === $entity instanceof GuardedAgainstDoubles) {
            $this->logger?->info("Saving {$location} without uniqueness check", $data);

            return $this->doSave($location, $data);
        }

        $existingId = $this->uniquenessCheckGateway->findIfExists(
            $entity->uniquenessConstraints(),
            $location,
            $data,
        );

        if (null === $existingId) {
            $this->logger?->info("Saving non-existing {$location}", $data);

            return $this->doSave($location, $data);
        }

        $strategy = $entity->strategyOnDuplicate();

        if (DuplicateStrategy::SKIP === $strategy) {
            $this->logger?->debug("Skipping existing {$location}");

            return $existingId;
        }

        if (DuplicateStrategy::UPDATE === $strategy) {
            $this->logger?->info("Updating existing {$location}", $data);

            $this->doUpdate($location, $existingId, $data);

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
                $nestedId = $this->doLoad($value);

                if (null === $nestedId) {
                    $this->logger?->warning(sprintf('Could not load entity of type %s.', $value::class));

                    continue;
                }

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

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    private function doSave(string $location, array $data): ?string
    {
        try {
            return $this->writeGateway->save($location, $data);
        } catch (Throwable $t) {
            $this->logger?->error(
                "Error saving {$location}",
                [
                    'data'    => $data,
                    'message' => $t->getMessage(),
                    'file'    => $t->getFile(),
                    'line'    => $t->getLine(),
                ],
            );

            return null;
        }
    }

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    private function doUpdate(string $location, string $existingId, array $data): void
    {
        try {
            $this->writeGateway->update($location, $existingId, $data);
        } catch (Throwable $t) {
            $this->logger?->error(
                "Error updating {$location} with id {$existingId}",
                [
                    'data'    => $data,
                    'message' => $t->getMessage(),
                    'file'    => $t->getFile(),
                    'line'    => $t->getLine(),
                ],
            );
        }
    }
}
