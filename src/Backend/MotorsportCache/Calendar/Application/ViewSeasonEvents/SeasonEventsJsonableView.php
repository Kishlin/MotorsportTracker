<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents;

use JsonException;
use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class SeasonEventsJsonableView extends JsonableView
{
    /**
     * @var array<string, array{
     *     id: string,
     *     slug: string,
     *     name: string,
     *     index: int,
     * }>
     */
    private array $events;

    /**
     * @return array<string, array{
     *     id: string,
     *     slug: string,
     *     name: string,
     *     index: int,
     * }>
     */
    public function toArray(): array
    {
        return $this->events;
    }

    /**
     * @throws JsonException
     */
    public static function fromSource(string $source): self
    {
        /** @var array<string, array{id: string, slug: string, name: string, index: int}> $events */
        $events = json_decode($source, true, 512, JSON_THROW_ON_ERROR);

        $view = new self();

        $view->events = $events;

        return $view;
    }

    /**
     * @param array<string, array{id: string, slug: string, name: string, index: int}> $data
     */
    public static function fromData(array $data): self
    {
        $view = new self();

        $view->events = $data;

        return $view;
    }
}
