<?php

declare(strict_types=1);

namespace App\Tests\Support\Unit\Contexts\Auth;

use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;

final class RecordingAccessTokenRepository implements AccessTokenRepository
{
    public array $savedAccessTokens = [];
    public array $revokedHashes = [];

    public function save(AccessToken $accessToken): void
    {
        $this->savedAccessTokens[] = $accessToken;
    }

    public function findValidByHash(TokenHash $hash): ?AccessToken
    {
        foreach ($this->savedAccessTokens as $accessToken) {
            if ($accessToken->tokenHash()->value() === $hash->value()) {
                return $accessToken;
            }
        }

        return null;
    }

    public function revokeByHash(TokenHash $hash): void
    {
        $this->revokedHashes[] = $hash;
    }
}
