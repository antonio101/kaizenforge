<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity;

use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Repository\DoctrineUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: DoctrineUserRepository::class)]
#[ORM\Table(name: 'kf_users')]
#[ORM\UniqueConstraint(name: 'uniq_kf_users_email', columns: ['email'])]
class DoctrineUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 180)]
    private string $email;

    #[ORM\Column(name: 'password_hash', type: 'string', length: 255)]
    private string $passwordHash;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(name: 'is_active', type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    /**
     * @param string[] $roles
     */
    public function __construct(
        string $id,
        string $email,
        string $passwordHash,
        array $roles,
        bool $isActive,
        \DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->email = mb_strtolower(trim($email));
        $this->passwordHash = $passwordHash;
        $this->roles = $roles;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return array_values(array_unique($this->roles));
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function eraseCredentials(): void
    {
    }
}
