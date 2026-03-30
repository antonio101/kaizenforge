<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;

final class InMemoryAccessTokenRepository implements AccessTokenRepository
{
    /**
     * @var list<AccessToken>
     */
    public array $savedAccessTokens = [];

    /**
     * @var list<TokenHash>
     */
    public array $requestedHashes = [];

    /**
     * @var list<TokenHash>
     */
    public array $revokedHashes = [];

    public function save(AccessToken $accessToken): void
    {
        $this->savedAccessTokens[] = $accessToken;
    }

    public function findValidByHash(TokenHash $hash): ?AccessToken
    {
        $this->requestedHashes[] = $hash;

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
