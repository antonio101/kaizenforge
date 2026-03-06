<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Contexts\Auth\Domain\ValueObject\UserId;
use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class KaizenForgeAuthFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $createdAt = new \DateTimeImmutable();

        $demo = new DoctrineUser(
            id: UserId::generate()->toString(),
            email: 'demo@kaizenforge.app',
            passwordHash: '',
            roles: ['ROLE_USER'],
            isActive: true,
            createdAt: $createdAt,
        );

        $demo->setPasswordHash(
            $this->passwordHasher->hashPassword($demo, 'Demo1234!')
        );

        $admin = new DoctrineUser(
            id: UserId::generate()->toString(),
            email: 'admin@kaizenforge.app',
            passwordHash: '',
            roles: ['ROLE_ADMIN'],
            isActive: true,
            createdAt: $createdAt,
        );

        $admin->setPasswordHash(
            $this->passwordHasher->hashPassword($admin, 'Demo1234!')
        );

        $manager->persist($demo);
        $manager->persist($admin);
        $manager->flush();
    }
}
