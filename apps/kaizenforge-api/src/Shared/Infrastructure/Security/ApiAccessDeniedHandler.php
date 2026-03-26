<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Infrastructure\Http\Error\ApiProblemResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

final readonly class ApiAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(
        private ApiProblemResponseFactory $responseFactory,
    ) {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        return $this->responseFactory->create(
            request: $request,
            status: Response::HTTP_FORBIDDEN,
            title: 'Forbidden',
            detail: 'You are not allowed to access this resource.',
        );
    }
}
