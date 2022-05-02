<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Domain\View;

final class CountryView
{
    private function __construct(
        private string $countryId,
        private string $countryCode,
    ) {
    }

    public function countryId(): string
    {
        return $this->countryId;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public static function fromScalars(string $id, string $code): self
    {
        return new self($id, $code);
    }
}
