<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Infrastructure\Http\Exception\MissingBearerToken;
use Symfony\Component\HttpFoundation\Request;

final class BearerTokenExtractor
{
    public function extract(Request $request): string
    {
        $authorization = (string) $request->headers->get('Authorization', '');

        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new MissingBearerToken();
        }

        $token = trim(substr($authorization, 7));

        if ($token === '') {
            throw new MissingBearerToken();
        }

        return $token;
    }
}
