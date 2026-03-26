<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Infrastructure\Http\Error\ApiProblemResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final readonly class ApiAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private ApiProblemResponseFactory $responseFactory,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return $this->responseFactory->create(
            request: $request,
            status: Response::HTTP_UNAUTHORIZED,
            title: 'Unauthorized',
            detail: 'Authentication is required to access this resource.',
            headers: ['WWW-Authenticate' => 'Bearer'],
        );
    }
}
