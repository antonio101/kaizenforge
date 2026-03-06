<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Port;

use App\Contexts\Auth\Domain\Model\User;

interface PasswordVerifier
{
    public function verify(User $user, string $plainPassword): bool;
}
