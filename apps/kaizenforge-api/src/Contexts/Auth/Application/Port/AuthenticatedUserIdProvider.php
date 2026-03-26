<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Port;

use App\Contexts\Auth\Domain\ValueObject\UserId;

interface AuthenticatedUserIdProvider
{
    public function get(): UserId;
}
