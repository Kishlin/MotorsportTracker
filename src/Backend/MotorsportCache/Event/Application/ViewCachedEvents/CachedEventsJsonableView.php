<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class CachedEventsJsonableView extends JsonableView
{
    /**
     * @var array<string, array{
     *     championship: string,
     *     year: int,
     *     event: string,
     * }>
     */
    private array $events;

    /**
     * @return array<string, array{
     *     championship: string,
     *     year: int,
     *     event: string,
     * }>
     */
    public function toArray(): array
    {
        return $this->events;
    }

    /**
     * @param array<array{championship: string, year: int, event: string}> $data
     */
    public static function fromData(array $data): self
    {
        $view = new self();

        $view->events = $data;

        return $view;
    }
}
