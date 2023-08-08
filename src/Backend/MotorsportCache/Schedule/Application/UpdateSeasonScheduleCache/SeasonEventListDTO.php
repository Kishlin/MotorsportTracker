<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache;

final readonly class SeasonEventListDTO
{
    /**
     * @param array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }> $data
     */
    private function __construct(
        private array $data,
    ) {
    }

    /**
     * @return array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
