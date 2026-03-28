<?php

declare(strict_types=1);

namespace App\Tests\Unit\Contexts\Auth\Domain\ValueObject;

use App\Contexts\Auth\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testItNormalizesTheEmail(): void
    {
        $email = Email::fromString('  Demo@KaizenForge.App  ');

        self::assertSame('demo@kaizenforge.app', $email->value());
    }

    public function testItRejectsAnInvalidEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromString('not-an-email');
    }
}
