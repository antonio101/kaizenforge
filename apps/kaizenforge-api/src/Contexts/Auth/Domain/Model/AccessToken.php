<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\Model;

use App\Contexts\Auth\Domain\ValueObject\TokenHash;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use Symfony\Component\Uid\Uuid;

final class AccessToken
{
    private function __construct(
        private string $id,
        private UserId $userId,
        private TokenHash $tokenHash,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $expiresAt,
        private ?\DateTimeImmutable $revokedAt,
    ) {
    }

    public static function issue(
        UserId $userId,
        string $plainToken,
        \DateTimeImmutable $now,
        \DateInterval $ttl,
    ): self {
        return new self(
            id: Uuid::v7()->toRfc4122(),
            userId: $userId,
            tokenHash: TokenHash::fromPlainToken($plainToken),
            createdAt: $now,
            expiresAt: $now->add($ttl),
            revokedAt: null,
        );
    }

    public static function reconstitute(
        string $id,
        UserId $userId,
        TokenHash $tokenHash,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $expiresAt,
        ?\DateTimeImmutable $revokedAt,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            tokenHash: $tokenHash,
            createdAt: $createdAt,
            expiresAt: $expiresAt,
            revokedAt: $revokedAt,
        );
    }

    public function revoke(\DateTimeImmutable $now): void
    {
        if ($this->revokedAt !== null) {
            return;
        }

        $this->revokedAt = $now;
    }

    public function isValidAt(\DateTimeImmutable $now): bool
    {
        return $this->revokedAt === null && $this->expiresAt > $now;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function tokenHash(): TokenHash
    {
        return $this->tokenHash;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function expiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function revokedAt(): ?\DateTimeImmutable
    {
        return $this->revokedAt;
    }
}
