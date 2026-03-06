<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\DTO\AuthenticatedUserView;
use App\Contexts\Auth\Domain\Model\User;

final class MeHandler
{
    public function __invoke(User $user): AuthenticatedUserView
    {
        return new AuthenticatedUserView(
            id: $user->id()->toString(),
            email: $user->email()->value(),
            roles: $user->roles(),
        );
    }
}
