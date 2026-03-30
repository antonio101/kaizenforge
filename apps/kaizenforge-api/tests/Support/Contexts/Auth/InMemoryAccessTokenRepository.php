<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;
use App\Shared\Domain\Clock\Clock;
use App\Shared\Infrastructure\Clock\SystemClock;

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

    private readonly Clock $clock;

    public function __construct(?Clock $clock = null)
    {
        $this->clock = $clock ?? new SystemClock();
    }

    public function save(AccessToken $accessToken): void
    {
        $this->savedAccessTokens[] = $accessToken;
    }

    public function findValidByHash(TokenHash $hash): ?AccessToken
    {
        $this->requestedHashes[] = $hash;

        foreach ($this->savedAccessTokens as $accessToken) {
            if ($accessToken->tokenHash()->value() !== $hash->value()) {
                continue;
            }

            return $accessToken->isValidAt($this->clock->now())
                ? $accessToken
                : null;
        }

        return null;
    }

    public function revokeByHash(TokenHash $hash): void
    {
        $this->revokedHashes[] = $hash;

        foreach ($this->savedAccessTokens as $accessToken) {
            if ($accessToken->tokenHash()->value() !== $hash->value()) {
                continue;
            }

            $accessToken->revoke($this->clock->now());

            return;
        }
    }
}
