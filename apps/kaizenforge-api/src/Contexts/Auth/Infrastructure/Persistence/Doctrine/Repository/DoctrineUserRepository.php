<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Repository;

use App\Contexts\Auth\Domain\Model\User;
use App\Contexts\Auth\Domain\Repository\UserRepository;
use App\Contexts\Auth\Domain\ValueObject\Email;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

final class DoctrineUserRepository extends ServiceEntityRepository implements UserRepository, PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineUser::class);
    }

    public function findByEmail(Email $email): ?User
    {
        $entity = $this->findOneBy(['email' => $email->value()]);

        return $entity instanceof DoctrineUser ? $this->mapToDomain($entity) : null;
    }

    public function findById(UserId $id): ?User
    {
        $entity = $this->find($id->toString());

        return $entity instanceof DoctrineUser ? $this->mapToDomain($entity) : null;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof DoctrineUser) {
            throw new \InvalidArgumentException(sprintf('Expected %s, got %s.', DoctrineUser::class, $user::class));
        }

        $user->setPasswordHash($newHashedPassword);
        $this->getEntityManager()->flush();
    }

    private function mapToDomain(DoctrineUser $entity): User
    {
        return new User(
            id: UserId::fromString($entity->id()),
            email: Email::fromString($entity->email()),
            passwordHash: $entity->getPassword(),
            roles: $entity->getRoles(),
            isActive: $entity->isActive(),
        );
    }
}
