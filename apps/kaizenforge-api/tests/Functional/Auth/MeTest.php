<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Support\Functional\ApiFunctionalTestCase;
use App\Tests\Support\Functional\Auth\AuthenticatesApiRequests;

final class MeTest extends ApiFunctionalTestCase
{
    use AuthenticatesApiRequests;

    public function testItReturnsTheAuthenticatedUser(): void
    {
        $client = $this->createApiClient();
        $token = $this->authenticate($client);

        $this->jsonRequest($client, 'GET', '/api/v1/auth/me', [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertSame(
            'application/json',
            $client->getResponse()->headers->get('content-type')
        );

        $data = $this->responseJson($client);

        self::assertSame(['id', 'email', 'roles'], array_keys($data));
        $this->assertUuidString($data['id']);
        self::assertSame('demo@kaizenforge.app', $data['email']);
        self::assertSame(['ROLE_USER'], $data['roles']);
    }

    public function testItReturnsUnauthorizedWithoutToken(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'GET', '/api/v1/auth/me');

        $this->assertProblemDetails(
            $client,
            401,
            'Unauthorized',
            'Authentication is required to access this resource.'
        );
    }

    public function testItReturnsUnauthorizedForInvalidToken(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'GET', '/api/v1/auth/me', [], [
            'HTTP_AUTHORIZATION' => 'Bearer invalid-token',
        ]);

        $this->assertProblemDetails(
            $client,
            401,
            'Unauthorized',
            'Invalid or expired access token.'
        );
    }
}