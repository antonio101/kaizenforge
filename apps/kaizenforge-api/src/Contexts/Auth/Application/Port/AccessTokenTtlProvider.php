<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Port;

interface AccessTokenTtlProvider
{
    public function get(): \DateInterval;
}
