<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Infrastructure\Http\Error\ApiProblemResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

final readonly class ApiAuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(
        private ApiProblemResponseFactory $responseFactory,
    ) {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->responseFactory->create(
            request: $request,
            status: Response::HTTP_UNAUTHORIZED,
            title: 'Unauthorized',
            detail: 'Invalid or expired access token.',
            headers: ['WWW-Authenticate' => 'Bearer'],
        );
    }
}
