<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Support\Functional\ApiFunctionalTestCase;
use App\Tests\Support\Functional\Auth\AuthenticatesApiRequests;

final class LogoutTest extends ApiFunctionalTestCase
{
    use AuthenticatesApiRequests;

    public function testItRevokesTheCurrentAccessToken(): void
    {
        $client = $this->createApiClient();
        $token = $this->authenticate($client);

        $this->jsonRequest($client, 'POST', '/api/v1/auth/logout', [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertSame(
            ['success' => true],
            $this->responseJson($client)
        );

        $this->jsonRequest($client, 'GET', '/api/v1/auth/me', [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
        ]);

        $this->assertProblemDetails(
            $client,
            401,
            'Unauthorized',
            'Invalid or expired access token.'
        );
    }

    public function testItRequiresAuthenticationToLogout(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'POST', '/api/v1/auth/logout');

        $this->assertProblemDetails(
            $client,
            401,
            'Unauthorized',
            'Authentication is required to access this resource.'
        );
    }
}