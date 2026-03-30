<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Domain\Model\User;
use App\Contexts\Auth\Domain\Repository\UserRepository;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Contexts\Auth\Domain\ValueObject\UserId;

final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var array<string, User>
     */
    private array $usersById = [];

    public int $findByEmailCalls = 0;
    public int $findByIdCalls = 0;

    /**
     * @param list<User> $users
     */
    public function __construct(array $users = [])
    {
        foreach ($users as $user) {
            $this->usersById[$user->id()->toString()] = $user;
        }
    }

    public function add(User $user): void
    {
        $this->usersById[$user->id()->toString()] = $user;
    }

    public function findByEmail(Email $email): ?User
    {
        ++$this->findByEmailCalls;

        foreach ($this->usersById as $user) {
            if ($user->email()->value() === $email->value()) {
                return $user;
            }
        }

        return null;
    }

    public function findById(UserId $id): ?User
    {
        ++$this->findByIdCalls;

        return $this->usersById[$id->toString()] ?? null;
    }
}
