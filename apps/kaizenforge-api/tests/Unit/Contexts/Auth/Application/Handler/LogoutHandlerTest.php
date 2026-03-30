<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Command\LogoutCommand;
use App\Contexts\Auth\Application\Handler\LogoutHandler;
use App\Tests\Support\Contexts\Auth\InMemoryAccessTokenRepository;
use PHPUnit\Framework\TestCase;

final class LogoutHandlerTest extends TestCase
{
    public function testItRevokesTheTokenUsingTheHashDerivedFromThePlainToken(): void
    {
        $accessTokenRepository = new InMemoryAccessTokenRepository();

        $handler = new LogoutHandler($accessTokenRepository);

        $handler(new LogoutCommand('plain-token'));

        self::assertCount(1, $accessTokenRepository->revokedHashes);
        self::assertSame(
            hash('sha256', 'plain-token'),
            $accessTokenRepository->revokedHashes[0]->value(),
        );
    }

    public function testItRejectsAnEmptyPlainToken(): void
    {
        $accessTokenRepository = new InMemoryAccessTokenRepository();

        $handler = new LogoutHandler($accessTokenRepository);

        $this->expectException(\InvalidArgumentException::class);

        try {
            $handler(new LogoutCommand(''));
        } finally {
            self::assertCount(0, $accessTokenRepository->revokedHashes);
        }
    }
}
