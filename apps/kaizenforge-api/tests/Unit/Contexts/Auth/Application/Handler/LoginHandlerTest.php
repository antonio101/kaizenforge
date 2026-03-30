<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Command\LoginCommand;
use App\Contexts\Auth\Application\Exception\InvalidCredentials;
use App\Contexts\Auth\Application\Handler\LoginHandler;
use App\Tests\Support\Contexts\Auth\FixedTokenGenerator;
use App\Tests\Support\Contexts\Auth\InMemoryAccessTokenRepository;
use App\Tests\Support\Contexts\Auth\InMemoryUserRepository;
use App\Tests\Support\Contexts\Auth\SpyPasswordVerifier;
use App\Tests\Support\Contexts\Auth\StaticAccessTokenTtlProvider;
use App\Tests\Support\Contexts\Auth\UserBuilder;
use App\Tests\Support\Shared\Clock\FixedClock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class LoginHandlerTest extends TestCase
{
    public function testItAuthenticatesAnActiveUserAndPersistsANewAccessToken(): void
    {
        $user = UserBuilder::anActiveUser()
            ->withEmail('demo@kaizenforge.app')
            ->build();

        $userRepository = new InMemoryUserRepository([$user]);
        $passwordVerifier = new SpyPasswordVerifier(true);
        $accessTokenRepository = new InMemoryAccessTokenRepository();
        $tokenGenerator = new FixedTokenGenerator(['plain-token']);
        $ttlProvider = new StaticAccessTokenTtlProvider(new \DateInterval('PT1H'));
        $clock = new FixedClock(new \DateTimeImmutable('2026-03-06T12:00:00+00:00'));

        $handler = new LoginHandler(
            $userRepository,
            $passwordVerifier,
            $accessTokenRepository,
            $tokenGenerator,
            $ttlProvider,
            $clock,
        );

        $result = $handler(new LoginCommand(
            email: '  Demo@KaizenForge.App  ',
            password: 'Demo1234!',
        ));

        self::assertSame('plain-token', $result->accessToken);
        self::assertSame('Bearer', $result->tokenType);
        self::assertSame('2026-03-06T13:00:00+00:00', $result->expiresAt);

        self::assertSame($user->id()->toString(), $result->user->id);
        self::assertSame('demo@kaizenforge.app', $result->user->email);
        self::assertSame(['ROLE_USER'], $result->user->roles);

        self::assertSame(1, $userRepository->findByEmailCalls);
        self::assertSame(1, $passwordVerifier->calls);
        self::assertSame($user, $passwordVerifier->lastUser);
        self::assertSame('Demo1234!', $passwordVerifier->lastPlainPassword);
        self::assertSame(1, $tokenGenerator->calls);

        self::assertCount(1, $accessTokenRepository->savedAccessTokens);

        $savedAccessToken = $accessTokenRepository->savedAccessTokens[0];

        self::assertTrue(Uuid::isValid($savedAccessToken->id()));
        self::assertSame($user->id()->toString(), $savedAccessToken->userId()->toString());
        self::assertSame(hash('sha256', 'plain-token'), $savedAccessToken->tokenHash()->value());
        self::assertSame('2026-03-06T12:00:00+00:00', $savedAccessToken->createdAt()->format(DATE_ATOM));
        self::assertSame('2026-03-06T13:00:00+00:00', $savedAccessToken->expiresAt()->format(DATE_ATOM));
        self::assertNull($savedAccessToken->revokedAt());
    }

    public function testItThrowsInvalidCredentialsWhenTheUserCannotBeFound(): void
    {
        $userRepository = new InMemoryUserRepository();
        $passwordVerifier = new SpyPasswordVerifier(true);
        $accessTokenRepository = new InMemoryAccessTokenRepository();
        $tokenGenerator = new FixedTokenGenerator(['plain-token']);
        $ttlProvider = new StaticAccessTokenTtlProvider(new \DateInterval('PT1H'));
        $clock = new FixedClock(new \DateTimeImmutable('2026-03-06T12:00:00+00:00'));

        $handler = new LoginHandler(
            $userRepository,
            $passwordVerifier,
            $accessTokenRepository,
            $tokenGenerator,
            $ttlProvider,
            $clock,
        );

        $this->expectException(InvalidCredentials::class);

        try {
            $handler(new LoginCommand(
                email: 'missing@kaizenforge.app',
                password: 'Demo1234!',
            ));
        } finally {
            self::assertSame(1, $userRepository->findByEmailCalls);
            self::assertSame(0, $passwordVerifier->calls);
            self::assertSame(0, $tokenGenerator->calls);
            self::assertCount(0, $accessTokenRepository->savedAccessTokens);
        }
    }

    public function testItThrowsInvalidCredentialsWhenThePasswordIsWrong(): void
    {
        $user = UserBuilder::anActiveUser()->build();

        $userRepository = new InMemoryUserRepository([$user]);
        $passwordVerifier = new SpyPasswordVerifier(false);
        $accessTokenRepository = new InMemoryAccessTokenRepository();
        $tokenGenerator = new FixedTokenGenerator(['plain-token']);
        $ttlProvider = new StaticAccessTokenTtlProvider(new \DateInterval('PT1H'));
        $clock = new FixedClock(new \DateTimeImmutable('2026-03-06T12:00:00+00:00'));

        $handler = new LoginHandler(
            $userRepository,
            $passwordVerifier,
            $accessTokenRepository,
            $tokenGenerator,
            $ttlProvider,
            $clock,
        );

        $this->expectException(InvalidCredentials::class);

        try {
            $handler(new LoginCommand(
                email: 'demo@kaizenforge.app',
                password: 'wrong-password',
            ));
        } finally {
            self::assertSame(1, $userRepository->findByEmailCalls);
            self::assertSame(1, $passwordVerifier->calls);
            self::assertSame($user, $passwordVerifier->lastUser);
            self::assertSame('wrong-password', $passwordVerifier->lastPlainPassword);
            self::assertSame(0, $tokenGenerator->calls);
            self::assertCount(0, $accessTokenRepository->savedAccessTokens);
        }
    }

    public function testItThrowsInvalidCredentialsWhenTheUserIsInactive(): void
    {
        $user = UserBuilder::anInactiveUser()
            ->withEmail('demo@kaizenforge.app')
            ->build();

        $userRepository = new InMemoryUserRepository([$user]);
        $passwordVerifier = new SpyPasswordVerifier(true);
        $accessTokenRepository = new InMemoryAccessTokenRepository();
        $tokenGenerator = new FixedTokenGenerator(['plain-token']);
        $ttlProvider = new StaticAccessTokenTtlProvider(new \DateInterval('PT1H'));
        $clock = new FixedClock(new \DateTimeImmutable('2026-03-06T12:00:00+00:00'));

        $handler = new LoginHandler(
            $userRepository,
            $passwordVerifier,
            $accessTokenRepository,
            $tokenGenerator,
            $ttlProvider,
            $clock,
        );

        $this->expectException(InvalidCredentials::class);

        try {
            $handler(new LoginCommand(
                email: 'demo@kaizenforge.app',
                password: 'Demo1234!',
            ));
        } finally {
            self::assertSame(1, $userRepository->findByEmailCalls);
            self::assertSame(1, $passwordVerifier->calls);
            self::assertSame(0, $tokenGenerator->calls);
            self::assertCount(0, $accessTokenRepository->savedAccessTokens);
        }
    }

    public function testItThrowsInvalidCredentialsWhenTheEmailFormatIsInvalid(): void
    {
        $userRepository = new InMemoryUserRepository([
            UserBuilder::anActiveUser()->build(),
        ]);
        $passwordVerifier = new SpyPasswordVerifier(true);
        $accessTokenRepository = new InMemoryAccessTokenRepository();
        $tokenGenerator = new FixedTokenGenerator(['plain-token']);
        $ttlProvider = new StaticAccessTokenTtlProvider(new \DateInterval('PT1H'));
        $clock = new FixedClock(new \DateTimeImmutable('2026-03-06T12:00:00+00:00'));

        $handler = new LoginHandler(
            $userRepository,
            $passwordVerifier,
            $accessTokenRepository,
            $tokenGenerator,
            $ttlProvider,
            $clock,
        );

        $this->expectException(InvalidCredentials::class);

        try {
            $handler(new LoginCommand(
                email: 'not-an-email',
                password: 'Demo1234!',
            ));
        } finally {
            self::assertSame(0, $userRepository->findByEmailCalls);
            self::assertSame(0, $passwordVerifier->calls);
            self::assertSame(0, $tokenGenerator->calls);
            self::assertCount(0, $accessTokenRepository->savedAccessTokens);
        }
    }
}
