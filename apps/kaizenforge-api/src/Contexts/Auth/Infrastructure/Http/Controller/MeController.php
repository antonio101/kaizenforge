<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Infrastructure\Persistence\Doctrine\Entity\DoctrineUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class MeController extends AbstractController
{
    #[Route('/api/v1/auth/me', name: 'kf_auth_me', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof DoctrineUser) {
            return $this->json(['message' => 'Unauthenticated.'], 401);
        }

        return $this->json([
            'id' => $user->id(),
            'email' => $user->email(),
            'roles' => $user->getRoles(),
        ]);
    }
}
