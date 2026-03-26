<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Application\Handler\MeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class MeController extends AbstractController
{
    #[Route('/api/v1/auth/me', name: 'kf_auth_me', methods: ['GET'], format: 'json')]
    public function __invoke(
        MeHandler $handler,
    ): JsonResponse {
        return $this->json($handler());
    }
}
