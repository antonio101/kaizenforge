<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\DTO\AuthenticatedUserView;
use App\Contexts\Auth\Application\Exception\Unauthenticated;
use App\Contexts\Auth\Application\Port\AuthenticatedUserIdProvider;
use App\Contexts\Auth\Domain\Repository\UserRepository;

final readonly class MeHandler
{
    public function __construct(
        private AuthenticatedUserIdProvider $authenticatedUserIdProvider,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(): AuthenticatedUserView
    {
        $user = $this->userRepository->findById(
            $this->authenticatedUserIdProvider->get()
        );

        if ($user === null || !$user->isActive()) {
            throw new Unauthenticated();
        }

        return new AuthenticatedUserView(
            id: $user->id()->toString(),
            email: $user->email()->value(),
            roles: $user->roles(),
        );
    }
}
