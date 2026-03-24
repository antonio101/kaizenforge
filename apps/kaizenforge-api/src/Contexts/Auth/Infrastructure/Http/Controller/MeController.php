<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Application\Exception\AuthenticatedUserNotFound;
use App\Contexts\Auth\Application\Handler\MeHandler;
use App\Contexts\Auth\Domain\ValueObject\UserId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class MeController extends AbstractController
{
    #[Route('/api/v1/auth/me', name: 'kf_auth_me', methods: ['GET'])]
    public function __invoke(
        MeHandler $handler,
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user instanceof UserInterface) {
            return $this->json([
                'message' => 'Unauthenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $result = $handler(
                UserId::fromString($user->getUserIdentifier())
            );
        } catch (AuthenticatedUserNotFound | \InvalidArgumentException) {
            return $this->json([
                'message' => 'Unauthenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($result, Response::HTTP_OK);
    }
}
