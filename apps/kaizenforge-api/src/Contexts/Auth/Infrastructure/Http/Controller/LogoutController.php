<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Application\Command\LogoutCommand;
use App\Contexts\Auth\Application\Handler\LogoutHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    #[Route('/api/v1/auth/logout', name: 'kf_auth_logout', methods: ['POST'])]
    public function __invoke(
        Request $request,
        LogoutHandler $handler,
    ): JsonResponse {
        $authorization = $request->headers->get('Authorization', '');

        if (!str_starts_with($authorization, 'Bearer ')) {
            return $this->json(['message' => 'Missing bearer token.'], 400);
        }

        $plainToken = trim(substr($authorization, 7));

        if ($plainToken === '') {
            return $this->json(['message' => 'Missing bearer token.'], 400);
        }

        $handler(new LogoutCommand($plainToken));

        return $this->json([
            'success' => true,
        ]);
    }
}
