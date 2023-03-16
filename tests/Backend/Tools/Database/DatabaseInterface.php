<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Database;

interface DatabaseInterface
{
    public function refreshDatabase(): void;

    public function loadFixture(string $fixture): void;

    public function fixtureId(string $fixture): string;

    /**
     * @param array<string, null|bool|float|int|string> $params
     *
     * @return null|bool|float|int|string null if the query failed, the result otherwise
     */
    public function fetchOne(string $query, array $params = []): mixed;

    /**
     * @param array<string, null|bool|float|int|string> $params
     *
     * @return null|array<string, null|bool|float|int|string> null if the query failed, the result as an associative array otherwise
     */
    public function fetchAssociative(string $query, array $params = []): array|null;

    /**
     * @param array<string, null|bool|float|int|string> $params
     *
     * @return null|array<array<string, null|bool|float|int|string>> null if the query failed, the result as an array of associative array otherwise
     */
    public function fetchAllAssociative(string $query, array $params = []): array|null;

    /**
     * @param array<string, null|bool|float|int|string> $params
     */
    public function exec(string $query, array $params = []): void;

    public function close(): void;
}
