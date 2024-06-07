<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\Shared\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ErrorJsonResponseBuilder
{
    private function __construct(
        private JsonResponse $response,
    ) {}

    public static function new(): self
    {
        return new self(new JsonResponse());
    }

    public function withCode(int $code): self
    {
        $this->response->setStatusCode($code);

        return $this;
    }

    public function withMessage(string $message): self
    {
        $this->response->setData(['error' => $message]);

        return $this;
    }

    public function build(): JsonResponse
    {
        return $this->response;
    }
}
