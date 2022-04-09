<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Championship\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @method Season get(SeasonId $id)
 */
final class SeasonRepositorySpy extends AbstractRepositorySpy implements SeasonGateway
{
    public function save(Season $season): void
    {
        $this->objects[$season->id()->value()] = $season;
    }
}
