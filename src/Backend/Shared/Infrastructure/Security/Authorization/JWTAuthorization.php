<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Security\Authorization;

final class JWTAuthorization
{
    private function __construct(
        private string $token,
    ) {}

    public static function fromCookie(null|bool|float|int|string $cookieValue): self
    {
        if (false === is_string($cookieValue) || '' === $cookieValue) {
            throw new FailedToReadCookieException('Failed to read cookie.');
        }

        return new self($cookieValue);
    }

    public function token(): string
    {
        return $this->token;
    }
}
