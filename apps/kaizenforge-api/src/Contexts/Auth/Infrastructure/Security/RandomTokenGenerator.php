<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Security;

use App\Contexts\Auth\Domain\Service\TokenGenerator;

final class RandomTokenGenerator implements TokenGenerator
{
    public function generatePlainToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
