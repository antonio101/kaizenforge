<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\DTO\AuthenticatedUserView;
use App\Contexts\Auth\Application\Exception\AuthenticatedUserNotFound;
use App\Contexts\Auth\Domain\Repository\UserRepository;
use App\Contexts\Auth\Domain\ValueObject\UserId;

final readonly class MeHandler
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(UserId $authenticatedUserId): AuthenticatedUserView
    {
        $user = $this->userRepository->findById($authenticatedUserId);

        if ($user === null || !$user->isActive()) {
            throw new AuthenticatedUserNotFound();
        }

        return new AuthenticatedUserView(
            id: $user->id()->toString(),
            email: $user->email()->value(),
            roles: $user->roles(),
        );
    }
}
