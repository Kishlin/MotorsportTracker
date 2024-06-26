<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\DeprecatedGraphDeletedEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\FailedToSaveEventGraphEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\DeleteDeprecatedEventGraphGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\EventGraphGateway;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Throwable;

final readonly class GraphDataSaverUsingEntity implements GraphDataSaver
{
    public function __construct(
        private DeleteDeprecatedEventGraphGateway $deleteDeprecatedEventGraphGateway,
        private EventGraphGateway $eventGraphGateway,
        private EventDispatcher $eventDispatcher,
    ) {}

    public function save(string $event, Graph $graph): void
    {
        assert($graph instanceof EventGraph);

        if ($this->deleteDeprecatedEventGraphGateway->deleteForEvent($event, $graph->type()->value())) {
            $this->eventDispatcher->dispatch(DeprecatedGraphDeletedEvent::forEvent($event, $graph->type()->value()));
        }

        try {
            $this->eventGraphGateway->save($graph);
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(FailedToSaveEventGraphEvent::forThrowable($e));

            return;
        }

        $this->eventDispatcher->dispatch(...$graph->pullDomainEvents());
    }
}
