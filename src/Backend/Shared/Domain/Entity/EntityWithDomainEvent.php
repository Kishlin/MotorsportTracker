<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;

trait EntityWithDomainEvent
{
    /** @var DomainEvent[] */
    private array $domainEvents = [];

    /**
     * @return DomainEvent[]
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
