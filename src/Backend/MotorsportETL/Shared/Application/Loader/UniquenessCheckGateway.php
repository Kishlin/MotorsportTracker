<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application\Loader;

interface UniquenessCheckGateway
{
    /**
     * @param array<string[]>                           $uniquenessConstraints
     * @param array<string, null|bool|float|int|string> $data
     */
    public function findIfExists(array $uniquenessConstraints, string $location, array $data): ?string;
}
