<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO;

final readonly class SessionsListDTO
{
    /**
     * @param SessionDTO[] $sessions
     */
    private function __construct(
        private array $sessions
    ) {
    }

    /**
     * @return SessionDTO[]
     */
    public function list(): array
    {
        return $this->sessions;
    }

    /**
     * @param array<array{id: string, ref: string, event: string, season: string}> $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            array_map(
                static function (array $datum) {
                    return SessionDTO::fromData($datum);
                },
                $data,
            ),
        );
    }
}
