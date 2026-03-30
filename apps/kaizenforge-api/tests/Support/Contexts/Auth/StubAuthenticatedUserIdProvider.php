<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Application\Port\AuthenticatedUserIdProvider;
use App\Contexts\Auth\Domain\ValueObject\UserId;

final class StubAuthenticatedUserIdProvider implements AuthenticatedUserIdProvider
{
    public function __construct(
        private readonly UserId $userId,
    ) {
    }

    public function get(): UserId
    {
        return $this->userId;
    }
}
