<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\DataFix\Application\FixMissingConstructorTeams;

interface FixMissingConstructorTeamsGateway
{
    public function fixMissingData(): void;
}
