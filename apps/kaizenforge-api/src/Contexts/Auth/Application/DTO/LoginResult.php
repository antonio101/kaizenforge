<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\DTO;

use App\Contexts\Auth\Domain\Model\User;

final readonly class LoginResult
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public string $expiresAt,
        public AuthenticatedUserView $user,
    ) {
    }

    public static function fromDomain(
        string $plainToken,
        \DateTimeImmutable $expiresAt,
        User $user,
    ): self {
        return new self(
            accessToken: $plainToken,
            tokenType: 'Bearer',
            expiresAt: $expiresAt->format(DATE_ATOM),
            user: new AuthenticatedUserView(
                id: $user->id()->toString(),
                email: $user->email()->value(),
                roles: $user->roles(),
            ),
        );
    }
}
