<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Security;

use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

final readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $tokenHash = TokenHash::fromPlainToken($accessToken);

        $token = $this->accessTokenRepository->findValidByHash($tokenHash);

        if ($token === null) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($token->userId()->toString());
    }
}
