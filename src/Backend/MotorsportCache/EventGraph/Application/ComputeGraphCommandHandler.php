<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\NoSessionFoundEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\EventSessionsGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

abstract class ComputeGraphCommandHandler implements CommandHandler
{
    /** @var array<string, int> */
    private array $colorCountCache;

    protected function __construct(
        private readonly EventSessionsGateway $eventSessionsGateway,
        private readonly GraphDataSaver $graphDataSaver,
        private readonly EventDispatcher $eventDispatcher,
    ) {}

    protected function doInvoke(ComputeGraphCommand $command): void
    {
        $sessionsToCompute = $this->eventSessionsGateway->findForEvent($command->eventId())->sessions();

        if (empty($sessionsToCompute)) {
            $this->eventDispatcher->dispatch(NoSessionFoundEvent::create());

            return;
        }

        $dataPerSession = [];
        foreach ($sessionsToCompute as $session) {
            $this->colorCountCache = [];

            $data = $this->computeDataForSession($session);

            if (false === empty($data)) {
                $dataPerSession[$session['session']] = $data;
            }
        }

        if (empty($dataPerSession)) {
            return;
        }

        $graph = $this->createGraph($dataPerSession);

        $this->graphDataSaver->save($command->eventId(), $graph);
    }

    protected function nextIndexForColor(string $color): int
    {
        if (false === array_key_exists($color, $this->colorCountCache)) {
            $this->colorCountCache[$color] = -1;
        }

        ++$this->colorCountCache[$color];

        return $this->colorCountCache[$color];
    }

    /**
     * @param array{session: string, type: string} $session
     *
     * @return array<string, mixed>
     */
    abstract protected function computeDataForSession(array $session): array;

    /**
     * @param array<string, mixed> $dataPerSession
     */
    abstract protected function createGraph(array $dataPerSession): Graph;
}
