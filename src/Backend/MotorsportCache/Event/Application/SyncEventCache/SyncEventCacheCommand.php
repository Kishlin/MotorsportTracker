<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class SyncEventCacheCommand implements Command
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}
