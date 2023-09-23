<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Loader;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository\EntityRepository;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository\UniquenessCheckRepository;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;
use Kishlin\Backend\Shared\Domain\Entity\Mapped;
use Kishlin\Backend\Shared\Infrastructure\Persistence\LocationComputer;
use Psr\Log\LoggerInterface;

final readonly class LoaderUsingRepositories implements Loader
{
    public function __construct(
        private UniquenessCheckRepository $uniquenessCheckRepository,
        private EntityRepository $writeRepository,
        private LocationComputer $locationComputer,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function load(Entity $entity): void
    {
        $this->doLoad($entity);
    }

    public function doLoad(Entity $entity): string
    {
        $location = $this->locationComputer->computeLocation($entity);
        $data     = $this->computeData($entity->mappedData());

        if (false === $entity instanceof GuardedAgainstDoubles) {
            $this->logger?->info("Saving {$location} without uniqueness check", $data);

            return $this->writeRepository->save($location, $data);
        }

        $existingId = $this->uniquenessCheckRepository->findIfExists(
            $entity->uniquenessConstraints(),
            $location,
            $data,
        );

        if (null === $existingId) {
            $this->logger?->info("Saving non-existing {$location}", $data);

            return $this->writeRepository->save($location, $data);
        }

        $strategy = $entity->strategyOnDuplicate();

        if (DuplicateStrategy::SKIP === $strategy) {
            $this->logger?->debug("Skipping existing {$location}");

            return $existingId;
        }
    }

    /**
     * @param array<string, null|bool|Entity|float|int|Mapped|string> $mappedDetails
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

            $data[$key] = $value;
        }

        return $data;
    }
}
