<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\Result;

interface Result
{
    public function isOk(): bool;

    public function isFail(): bool;

    public function failMessage(): ResultFailure;

    /**
     * @return array<int, array<string, null|bool|float|int|string>>
     */
    public function fetchAllAssociative(): array;

    /**
     * @return array<string, null|bool|float|int|string>
     */
    public function fetchAssociative(): array;

    public function fetchOne(): null|bool|float|int|string;
}
