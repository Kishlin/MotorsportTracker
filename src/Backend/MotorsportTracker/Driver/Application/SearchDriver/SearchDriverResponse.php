<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchDriverResponse implements Response
{
    private function __construct(
        private DriverId $driverId,
    ) {
    }

    public function driverId(): DriverId
    {
        return $this->driverId;
    }

    public static function fromScalars(DriverId $driverId): self
    {
        return new self($driverId);
    }
}
