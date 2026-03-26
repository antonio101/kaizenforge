<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Command\LoginCommand;
use App\Contexts\Auth\Application\DTO\LoginResult;
use App\Contexts\Auth\Application\Exception\InvalidCredentials;
use App\Contexts\Auth\Application\Port\AccessTokenTtlProvider;
use App\Contexts\Auth\Application\Port\PasswordVerifier;
use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\Repository\UserRepository;
use App\Contexts\Auth\Domain\Service\TokenGenerator;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Shared\Domain\Clock\Clock;

final readonly class LoginHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordVerifier $passwordVerifier,
        private AccessTokenRepository $accessTokenRepository,
        private TokenGenerator $tokenGenerator,
        private AccessTokenTtlProvider $accessTokenTtlProvider,
        private Clock $clock,
    ) {
    }

    public function __invoke(LoginCommand $command): LoginResult
    {
        try {
            $email = Email::fromString($command->email);
        } catch (\InvalidArgumentException) {
            throw new InvalidCredentials();
        }

        $user = $this->userRepository->findByEmail($email);

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
            ttl: $this->accessTokenTtlProvider->get(),
        );

        $this->accessTokenRepository->save($accessToken);

        return LoginResult::fromDomain(
            plainToken: $plainToken,
            expiresAt: $accessToken->expiresAt(),
            user: $user,
        );
    }
}
