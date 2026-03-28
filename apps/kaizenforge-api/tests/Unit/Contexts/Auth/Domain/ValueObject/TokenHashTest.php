<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Domain\ValueObject;

use App\Contexts\Auth\Domain\ValueObject\TokenHash;
use PHPUnit\Framework\TestCase;

final class TokenHashTest extends TestCase
{
    public function testItBuildsAHashFromAPlainToken(): void
    {
        $hash = TokenHash::fromPlainToken('plain-token');

        self::assertSame(hash('sha256', 'plain-token'), $hash->value());
    }

    public function testItAcceptsAValidStoredHash(): void
    {
        $value = hash('sha256', 'plain-token');

        $hash = TokenHash::fromStoredHash($value);

        self::assertSame($value, $hash->value());
    }

    public function testItRejectsAnEmptyPlainToken(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TokenHash::fromPlainToken('');
    }

    public function testItRejectsAnInvalidStoredHash(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TokenHash::fromStoredHash('invalid');
    }
}
