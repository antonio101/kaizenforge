<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Error;

use App\Contexts\Auth\Application\Exception\InvalidCredentials;
use App\Contexts\Auth\Application\Exception\Unauthenticated;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class HttpExceptionMapper
{
    public function statusCodeFor(\Throwable $throwable): int
    {
        return match (true) {
            $throwable instanceof InvalidCredentials => Response::HTTP_UNAUTHORIZED,
            $throwable instanceof Unauthenticated => Response::HTTP_UNAUTHORIZED,
            $throwable instanceof HttpExceptionInterface => $throwable->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    public function titleFor(int $statusCode): string
    {
        return Response::$statusTexts[$statusCode] ?? 'Unknown Error';
    }

    public function detailFor(\Throwable $throwable, int $statusCode): string
    {
        if ($throwable instanceof InvalidCredentials) {
            return 'Invalid credentials.';
        }

        if ($throwable instanceof Unauthenticated) {
            return 'Authentication is required to access this resource.';
        }

        if ($statusCode === Response::HTTP_INTERNAL_SERVER_ERROR) {
            return 'An unexpected error occurred.';
        }

        $message = trim($throwable->getMessage());

        return $message !== ''
            ? $message
            : $this->titleFor($statusCode);
    }
}