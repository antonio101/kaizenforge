<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\Repository;

use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;

interface AccessTokenRepository
{
    public function save(AccessToken $accessToken): void;

    public function findValidByHash(TokenHash $hash): ?AccessToken;

    public function revokeByHash(TokenHash $hash): void;
}
