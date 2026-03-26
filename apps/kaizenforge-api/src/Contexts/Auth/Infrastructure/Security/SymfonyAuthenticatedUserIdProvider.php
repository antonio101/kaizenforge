<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Security;

use App\Contexts\Auth\Application\Exception\Unauthenticated;
use App\Contexts\Auth\Application\Port\AuthenticatedUserIdProvider;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class SymfonyAuthenticatedUserIdProvider implements AuthenticatedUserIdProvider
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function get(): UserId
    {
        $user = $this->security->getUser();

        if ($user === null) {
            throw new Unauthenticated();
        }

        try {
            return UserId::fromString($user->getUserIdentifier());
        } catch (\InvalidArgumentException) {
            throw new Unauthenticated();
        }
    }
}
