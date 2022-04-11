<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Domain\Entity;

use Kishlin\Backend\Country\Domain\DomainEvent\CountryCreatedDomainEvent;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Country extends AggregateRoot
{
    public function __construct(
        private CountryId $id,
        private CountryCode $code,
    ) {
    }

    public static function create(CountryId $id, CountryCode $code): self
    {
        $country = new self($id, $code);

        $country->record(new CountryCreatedDomainEvent($id));

        return $country;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(CountryId $id, CountryCode $code): self
    {
        return new self($id, $code);
    }

    public function id(): CountryId
    {
        return $this->id;
    }

    public function code(): CountryCode
    {
        return $this->code;
    }
}
