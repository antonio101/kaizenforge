<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\ValueObject;

final readonly class TokenHash
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function fromPlainToken(string $plainToken): self
    {
        if ($plainToken === '') {
            throw new \InvalidArgumentException('Token cannot be empty.');
        }

        return new self(hash('sha256', $plainToken));
    }

    public static function fromStoredHash(string $value): self
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $value)) {
            throw new \InvalidArgumentException('Invalid token hash.');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
