<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Application\Command\LogoutCommand;
use App\Contexts\Auth\Application\Handler\LogoutHandler;
use App\Shared\Infrastructure\Security\BearerTokenExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    #[Route('/api/v1/auth/logout', name: 'kf_auth_logout', methods: ['POST'], format: 'json')]
    public function __invoke(
        Request $request,
        LogoutHandler $handler,
        BearerTokenExtractor $bearerTokenExtractor,
    ): JsonResponse {
        $plainToken = $bearerTokenExtractor->extract($request);

        $handler(new LogoutCommand($plainToken));

        return $this->json([
            'success' => true,
        ]);
    }
}
