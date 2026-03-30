<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Domain\Model\User;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Contexts\Auth\Domain\ValueObject\UserId;

final class UserBuilder
{
    private UserId $id;
    private string $email = 'demo@kaizenforge.app';
    private string $passwordHash = 'stored-password-hash';
    private array $roles = ['ROLE_USER'];
    private bool $isActive = true;

    private function __construct()
    {
        $this->id = UserId::generate();
    }

    public static function anActiveUser(): self
    {
        return new self();
    }

    public static function anInactiveUser(): self
    {
        return (new self())->inactive();
    }

    public function withId(UserId $id): self
    {
        $builder = clone $this;
        $builder->id = $id;

        return $builder;
    }

    public function withEmail(string $email): self
    {
        $builder = clone $this;
        $builder->email = $email;

        return $builder;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $builder = clone $this;
        $builder->passwordHash = $passwordHash;

        return $builder;
    }

    public function withRoles(array $roles): self
    {
        $builder = clone $this;
        $builder->roles = $roles;

        return $builder;
    }

    public function active(): self
    {
        $builder = clone $this;
        $builder->isActive = true;

        return $builder;
    }

    public function inactive(): self
    {
        $builder = clone $this;
        $builder->isActive = false;

        return $builder;
    }

    public function build(): User
    {
        return new User(
            id: $this->id,
            email: Email::fromString($this->email),
            passwordHash: $this->passwordHash,
            roles: $this->roles,
            isActive: $this->isActive,
        );
    }
}
