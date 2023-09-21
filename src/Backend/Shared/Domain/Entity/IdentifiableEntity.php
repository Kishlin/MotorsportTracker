<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

/**
 * @property UuidValueObject $id
 */
trait IdentifiableEntity
{
    public function id(): UuidValueObject
    {
        return $this->id;
    }
}
