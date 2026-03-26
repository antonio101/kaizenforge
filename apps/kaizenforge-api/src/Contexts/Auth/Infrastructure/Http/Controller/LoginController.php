<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Application\Command\LoginCommand;
use App\Contexts\Auth\Application\Handler\LoginHandler;
use App\Contexts\Auth\Infrastructure\Http\Request\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    #[Route('/api/v1/auth/login', name: 'kf_auth_login', methods: ['POST'], format: 'json')]
    public function __invoke(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        )]
        LoginRequest $payload,
        LoginHandler $handler,
    ): JsonResponse {
        $result = $handler(new LoginCommand(
            email: (string) $payload->email,
            password: (string) $payload->password,
        ));

        return $this->json($result);
    }
}
