<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\DataFix\Application\FixMissingConstructorTeams;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class FixMissingConstructorTeamsCommand implements Command
{
    private function __construct(
    ) {}

    public static function create(): self
    {
        return new self();
    }
}
