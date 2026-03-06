<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Clock;

final class SystemClock
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
