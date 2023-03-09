<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity;

final class Series
{
    private function __construct(
        private readonly string $name,
        private readonly string $slug,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * @param array{
     *     name: string,
     *     slug: string,
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data['name'], $data['slug']);
    }
}
