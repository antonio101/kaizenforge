<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Command\LoginCommand;
use App\Contexts\Auth\Application\DTO\LoginResult;
use App\Contexts\Auth\Application\Port\PasswordVerifier;
use App\Contexts\Auth\Domain\Exception\InvalidCredentials;
use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\Repository\UserRepository;
use App\Contexts\Auth\Domain\Service\TokenGenerator;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Shared\Infrastructure\Clock\SystemClock;

final readonly class LoginHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordVerifier $passwordVerifier,
        private AccessTokenRepository $accessTokenRepository,
        private TokenGenerator $tokenGenerator,
        private SystemClock $clock,
    ) {
    }

    public function __invoke(LoginCommand $command): LoginResult
    {
        $user = $this->userRepository->findByEmail(
            Email::fromString($command->email)
        );

        if ($user === null || !$this->passwordVerifier->verify($user, $command->password)) {
            throw new InvalidCredentials();
        }

        if (!$user->isActive()) {
            throw new InvalidCredentials();
        }

        $plainToken = $this->tokenGenerator->generatePlainToken();

        $accessToken = AccessToken::issue(
            userId: $user->id(),
            plainToken: $plainToken,
            now: $this->clock->now(),
            ttl: new \DateInterval('P7D'),
        );

        $this->accessTokenRepository->save($accessToken);

        return LoginResult::fromDomain(
            plainToken: $plainToken,
            expiresAt: $accessToken->expiresAt(),
            user: $user,
        );
    }
}
