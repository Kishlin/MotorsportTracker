<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\DeleteSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SaveSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\SeasonEventsJsonableView;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\SeasonEventsNotFoundException;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\ViewSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\SeasonEvents;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property SeasonEvents[] $objects
 *
 * @method SeasonEvents[]    all()
 * @method null|SeasonEvents get(UuidValueObject $id)
 * @method SeasonEvents      safeGet(UuidValueObject $id)
 */
final class SeasonEventsRepositorySpy extends AbstractRepositorySpy implements SaveSeasonEventsGateway, DeleteSeasonEventsGateway, ViewSeasonEventsGateway
{
    public function save(SeasonEvents $seasonEvents): void
    {
        $this->add($seasonEvents);
    }

    public function deleteIfExists(StringValueObject $championship, StrictlyPositiveIntValueObject $year): bool
    {
        $toDelete = $this->find(StringHelper::slugify($championship->value()), $year->value());
        if (null === $toDelete) {
            return false;
        }

        unset($this->objects[$toDelete->id()->value()]);

        return true;
    }

    public function viewForSeason(string $championshipSlug, int $year): SeasonEventsJsonableView
    {
        $seasonEvents = $this->find($championshipSlug, $year);
        if (null === $seasonEvents) {
            throw new SeasonEventsNotFoundException();
        }

        return SeasonEventsJsonableView::fromData($seasonEvents->events()->data());
    }

    public function find(string $championship, int $year): ?SeasonEvents
    {
        foreach ($this->objects as $seasonEvents) {
            if ($championship === $seasonEvents->championship()->value() && $year === $seasonEvents->year()->value()) {
                return $seasonEvents;
            }
        }

        return null;
    }
}
