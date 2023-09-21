<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Event;

/**
 * EventSubscribers must have an __invoke() method, that will be called when the event (it subscribes to) occurs.
 * The subscriber will be given the event that was raised as the only parameter when invoked.
 */
interface EventListener
{
}
