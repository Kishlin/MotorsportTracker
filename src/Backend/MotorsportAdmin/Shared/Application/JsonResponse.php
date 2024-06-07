<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Application;

final readonly class JsonResponse
{
    /**
     * @param array<int|string, mixed> $data
     */
    private function __construct(
        private array $data,
    ) {}

    /**
     * @return array<int|string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array<int|string, mixed> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
