<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\Model;

use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Contexts\Auth\Domain\ValueObject\UserId;

final readonly class User
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        private UserId $id,
        private Email $email,
        private string $passwordHash,
        private array $roles,
        private bool $isActive,
    ) {
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
