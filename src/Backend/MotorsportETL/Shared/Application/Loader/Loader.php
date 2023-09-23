<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application\Loader;

use Kishlin\Backend\Shared\Domain\Entity\Entity;

interface Loader
{
    public function load(Entity $entity): void;
}
