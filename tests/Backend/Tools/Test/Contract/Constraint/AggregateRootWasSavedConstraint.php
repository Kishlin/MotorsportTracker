<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract\Constraint;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use PHPUnit\Framework\Constraint\Constraint;

final class AggregateRootWasSavedConstraint extends Constraint
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }
    
    public function toString(): string
    {
        return ' was saved to the database.';
    }

    protected function matches($other): bool
    {
        if(false === method_exists($other, 'id')) {
            return false;
        }

        $id = $other->id();

        if (false === $id instanceof UuidValueObject) {
            return false;
        }

        $repository = $this->entityManager->getRepository($other::class);

        return 1 === $repository->count(['id' => $id]);
    }


}
