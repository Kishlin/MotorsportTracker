<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverCountryId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverFirstname;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateDriverCommand implements Command
{
    private function __construct(
        private string $name,
        private string $firstname,
        private string $countryId,
    ) {
    }

    public function name(): DriverName
    {
        return new DriverName($this->name);
    }

    public function firstname(): DriverFirstname
    {
        return new DriverFirstname($this->firstname);
    }

    public function countryId(): DriverCountryId
    {
        return new DriverCountryId($this->countryId);
    }

    public static function fromScalars(string $name, string $firstname, string $countryCode): self
    {
        return new self($name, $firstname, $countryCode);
    }
}
