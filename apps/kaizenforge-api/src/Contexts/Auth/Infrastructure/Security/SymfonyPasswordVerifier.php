<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Security;

use App\Contexts\Auth\Application\Port\PasswordVerifier;
use App\Contexts\Auth\Domain\Model\User;
use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final readonly class SymfonyPasswordVerifier implements PasswordVerifier
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function verify(User $user, string $plainPassword): bool
    {
        $hasher = $this->passwordHasherFactory->getPasswordHasher(DoctrineUser::class);

        return $hasher->verify($user->passwordHash(), $plainPassword);
    }
}
