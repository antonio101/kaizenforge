<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Domain\Model;

use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class AccessTokenTest extends TestCase
{
    public function testIssuedTokenIsValidUntilItExpires(): void
    {
        $now = new \DateTimeImmutable('2026-03-06T12:00:00+00:00');
        $ttl = new \DateInterval('PT1H');

        $token = AccessToken::issue(
            UserId::generate(),
            'plain-token',
            $now,
            $ttl,
        );

        self::assertSame($now, $token->createdAt());
        self::assertEquals($now->add($ttl), $token->expiresAt());
        self::assertTrue($token->isValidAt($now));
        self::assertTrue($token->isValidAt($now->modify('+59 minutes')));
        self::assertFalse($token->isValidAt($now->modify('+60 minutes')));
    }

    public function testRevokedTokenIsNotValid(): void
    {
        $now = new \DateTimeImmutable('2026-03-06T12:00:00+00:00');

        $token = AccessToken::issue(
            UserId::generate(),
            'plain-token',
            $now,
            new \DateInterval('PT1H'),
        );

        $token->revoke($now->modify('+10 minutes'));

        self::assertNotNull($token->revokedAt());
        self::assertFalse($token->isValidAt($now->modify('+11 minutes')));
    }
}
