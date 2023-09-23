<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class VenueDetails implements Mapped
{
    private function __construct(
        private string $name,
        private string $ref,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'name' => $this->name,
            'ref'  => $this->ref,
        ];
    }

    /**
     * @param array{name: string, uuid: string, shortName: string, shortCode: string, picture: string} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['name'],
            $data['uuid'],
        );
    }
}
