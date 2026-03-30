<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Exception\Unauthenticated;
use App\Contexts\Auth\Application\Handler\MeHandler;
use App\Contexts\Auth\Application\Port\AuthenticatedUserIdProvider;
use App\Contexts\Auth\Domain\Model\User;
use App\Contexts\Auth\Domain\Repository\UserRepository;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class MeHandlerTest extends TestCase
{
    public function testItReturnsTheAuthenticatedUserView(): void
    {
        $user = new User(
            id: UserId::generate(),
            email: Email::fromString('demo@kaizenforge.app'),
            passwordHash: 'hash',
            roles: ['ROLE_USER'],
            isActive: true,
        );

        $provider = $this->createStub(AuthenticatedUserIdProvider::class);
        $provider->method('get')->willReturn($user->id());

        $repository = $this->createStub(UserRepository::class);
        $repository->method('findById')->willReturn($user);

        $handler = new MeHandler($provider, $repository);

        $result = $handler();

        self::assertSame($user->id()->toString(), $result->id);
        self::assertSame('demo@kaizenforge.app', $result->email);
    }

    public function testItThrowsUnauthenticatedWhenUserNotFound(): void
    {
        $provider = $this->createStub(AuthenticatedUserIdProvider::class);
        $provider->method('get')->willReturn(UserId::generate());

        $repository = $this->createStub(UserRepository::class);
        $repository->method('findById')->willReturn(null);

        $handler = new MeHandler($provider, $repository);

        $this->expectException(Unauthenticated::class);

        $handler();
    }
}
