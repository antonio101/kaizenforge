<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Repository;

use App\Contexts\Auth\Domain\Model\AccessToken;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity\DoctrineAccessToken;
use App\Shared\Domain\Clock\Clock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineAccessTokenRepository extends ServiceEntityRepository implements AccessTokenRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Clock $clock,
    ) {
        parent::__construct($registry, DoctrineAccessToken::class);
    }

    public function save(AccessToken $accessToken): void
    {
        $entity = new DoctrineAccessToken(
            id: $accessToken->id(),
            userId: $accessToken->userId()->toString(),
            tokenHash: $accessToken->tokenHash()->value(),
            createdAt: $accessToken->createdAt(),
            expiresAt: $accessToken->expiresAt(),
            revokedAt: $accessToken->revokedAt(),
        );

        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    public function findValidByHash(TokenHash $hash): ?AccessToken
    {
        /** @var DoctrineAccessToken|null $entity */
        $entity = $this->createQueryBuilder('t')
            ->andWhere('t.tokenHash = :tokenHash')
            ->andWhere('t.revokedAt IS NULL')
            ->andWhere('t.expiresAt > :now')
            ->setParameter('tokenHash', $hash->value())
            ->setParameter('now', $this->clock->now())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($entity === null) {
            return null;
        }

        return AccessToken::reconstitute(
            id: $entity->id(),
            userId: UserId::fromString($entity->userId()),
            tokenHash: TokenHash::fromStoredHash($entity->tokenHash()),
            createdAt: $entity->createdAt(),
            expiresAt: $entity->expiresAt(),
            revokedAt: $entity->revokedAt(),
        );
    }

    public function revokeByHash(TokenHash $hash): void
    {
        /** @var DoctrineAccessToken|null $entity */
        $entity = $this->findOneBy(['tokenHash' => $hash->value()]);

        if ($entity === null) {
            return;
        }

        $entity->revoke($this->clock->now());
        $this->getEntityManager()->flush();
    }
}
