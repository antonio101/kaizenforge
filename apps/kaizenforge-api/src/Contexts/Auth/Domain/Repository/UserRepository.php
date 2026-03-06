<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\Repository;

use App\Contexts\Auth\Domain\Model\User;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Contexts\Auth\Domain\ValueObject\UserId;

interface UserRepository
{
    public function findByEmail(Email $email): ?User;

    public function findById(UserId $id): ?User;
}
