<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Exception\Unauthenticated;
use App\Contexts\Auth\Application\Handler\MeHandler;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use App\Tests\Support\Contexts\Auth\InMemoryUserRepository;
use App\Tests\Support\Contexts\Auth\StubAuthenticatedUserIdProvider;
use App\Tests\Support\Contexts\Auth\UserBuilder;
use PHPUnit\Framework\TestCase;

final class MeHandlerTest extends TestCase
{
    public function testItReturnsTheAuthenticatedUserView(): void
    {
        $userId = UserId::generate();
        $user = UserBuilder::anActiveUser()
            ->withId($userId)
            ->withEmail('demo@kaizenforge.app')
            ->withRoles(['ROLE_USER'])
            ->build();

        $authenticatedUserIdProvider = new StubAuthenticatedUserIdProvider($userId);
        $userRepository = new InMemoryUserRepository([$user]);

        $handler = new MeHandler(
            $authenticatedUserIdProvider,
            $userRepository,
        );

        $result = $handler();

        self::assertSame(1, $userRepository->findByIdCalls);
        self::assertSame($userId->toString(), $result->id);
        self::assertSame('demo@kaizenforge.app', $result->email);
        self::assertSame(['ROLE_USER'], $result->roles);
    }

    public function testItThrowsUnauthenticatedWhenTheUserCannotBeFound(): void
    {
        $authenticatedUserIdProvider = new StubAuthenticatedUserIdProvider(UserId::generate());
        $userRepository = new InMemoryUserRepository();

        $handler = new MeHandler(
            $authenticatedUserIdProvider,
            $userRepository,
        );

        $this->expectException(Unauthenticated::class);

        try {
            $handler();
        } finally {
            self::assertSame(1, $userRepository->findByIdCalls);
        }
    }

    public function testItThrowsUnauthenticatedWhenTheUserIsInactive(): void
    {
        $userId = UserId::generate();
        $user = UserBuilder::anInactiveUser()
            ->withId($userId)
            ->withEmail('disabled@kaizenforge.app')
            ->build();

        $authenticatedUserIdProvider = new StubAuthenticatedUserIdProvider($userId);
        $userRepository = new InMemoryUserRepository([$user]);

        $handler = new MeHandler(
            $authenticatedUserIdProvider,
            $userRepository,
        );

        $this->expectException(Unauthenticated::class);

        try {
            $handler();
        } finally {
            self::assertSame(1, $userRepository->findByIdCalls);
        }
    }
}
