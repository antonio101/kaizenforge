<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Application\Port\AccessTokenTtlProvider;

final class StaticAccessTokenTtlProvider implements AccessTokenTtlProvider
{
    public function __construct(
        private readonly \DateInterval $ttl,
    ) {
    }

    public function get(): \DateInterval
    {
        return $this->ttl;
    }
}
