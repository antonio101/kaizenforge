<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\ValueObject;

final readonly class Email
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        $normalized = mb_strtolower(trim($value));

        if (!filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email.');
        }

        return new self($normalized);
    }

    public function value(): string
    {
        return $this->value;
    }
}
