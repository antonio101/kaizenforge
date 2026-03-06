<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity;

use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Repository\DoctrineAccessTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineAccessTokenRepository::class)]
#[ORM\Table(name: 'kf_access_tokens')]
#[ORM\Index(name: 'idx_kf_access_tokens_user_id', columns: ['user_id'])]
#[ORM\Index(name: 'idx_kf_access_tokens_expires_at', columns: ['expires_at'])]
#[ORM\UniqueConstraint(name: 'uniq_kf_access_tokens_token_hash', columns: ['token_hash'])]
class DoctrineAccessToken
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'user_id', type: 'string', length: 36)]
    private string $userId;

    #[ORM\Column(name: 'token_hash', type: 'string', length: 64)]
    private string $tokenHash;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'expires_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $expiresAt;

    #[ORM\Column(name: 'revoked_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $revokedAt;

    public function __construct(
        string $id,
        string $userId,
        string $tokenHash,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $expiresAt,
        ?\DateTimeImmutable $revokedAt,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->tokenHash = $tokenHash;
        $this->createdAt = $createdAt;
        $this->expiresAt = $expiresAt;
        $this->revokedAt = $revokedAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function tokenHash(): string
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

    public function revoke(\DateTimeImmutable $revokedAt): void
    {
        if ($this->revokedAt !== null) {
            return;
        }

        $this->revokedAt = $revokedAt;
    }
}
