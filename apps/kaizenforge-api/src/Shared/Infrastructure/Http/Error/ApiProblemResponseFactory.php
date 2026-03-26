<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Error;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ApiProblemResponseFactory
{
    public function create(
        Request $request,
        int $status,
        string $title,
        string $detail,
        string $type = 'about:blank',
        array $extra = [],
        array $headers = [],
    ): JsonResponse {
        $payload = array_merge([
            'type' => $type,
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => $request->getPathInfo(),
        ], $extra);

        return new JsonResponse(
            data: $payload,
            status: $status,
            headers: array_merge(
                ['Content-Type' => 'application/problem+json'],
                $headers,
            ),
        );
    }
}
