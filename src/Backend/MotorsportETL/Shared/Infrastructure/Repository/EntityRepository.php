<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class EntityRepository extends WriteRepository implements CoreRepositoryInterface
{
    public function __construct(
        private UuidGenerator $uuidGenerator,
        Connection $connection,
    ) {
        parent::__construct($connection);
    }

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    public function save(string $location, array $data): string
    {
        if (false === array_key_exists('id', $data)) {
            $data['id'] = $this->uuidGenerator->uuid4();
        }

        $this->persist($location, $data);

        return (string) $data['id'];
    }
}
