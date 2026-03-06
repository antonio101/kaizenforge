<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\ValueObject;

use Symfony\Component\Uid\Uuid;

final readonly class UserId
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function generate(): self
    {
        return new self(Uuid::v7()->toRfc4122());
    }

    public static function fromString(string $value): self
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid user id.');
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
