<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

abstract class ScrapCachableResourceCommand implements Command
{
    protected function __construct(
        private bool $cacheMustBeInvalidated = false,
    ) {
    }

    public function cacheMustBeInvalidated(): bool
    {
        return $this->cacheMustBeInvalidated;
    }

    public function invalidateCache(): self
    {
        $this->cacheMustBeInvalidated = true;

        return $this;
    }
}
