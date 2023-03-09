<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity;

final class Session
{
    private function __construct(
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly string $slug,
        private readonly ?string $code,
        private readonly ?string $status,
        private readonly bool $hasResults,
        private ?int $startTimeUtc,
        private ?int $endTimeUtc,
    ) {
    }

    public function copyDateTimes(self $other): void
    {
        $this->startTimeUtc = $other->startTimeUtc;
        $this->endTimeUtc   = $other->endTimeUtc;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function shortName(): ?string
    {
        return $this->shortName;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function code(): ?string
    {
        return $this->code;
    }

    public function status(): ?string
    {
        return $this->status;
    }

    public function hasResults(): bool
    {
        return $this->hasResults;
    }

    public function startTimeUtc(): null|int
    {
        return $this->startTimeUtc;
    }

    public function endTimeUtc(): ?int
    {
        return $this->endTimeUtc;
    }

    /**
     * @param array{
     *     session: array{
     *         name: string,
     *         shortName: null|string,
     *         slug: string,
     *         code: null|string,
     *     },
     *     status: null|string,
     *     hasResults: bool,
     *     startTimeUtc: null|int,
     *     endTimeUtc: null|int,
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['session']['name'],
            $data['session']['shortName'],
            $data['session']['slug'],
            $data['session']['code'],
            $data['status'],
            $data['hasResults'],
            $data['startTimeUtc'],
            $data['endTimeUtc'],
        );
    }
}
