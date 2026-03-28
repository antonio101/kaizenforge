<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Support\Functional\ApiFunctionalTestCase;

final class LoginTest extends ApiFunctionalTestCase
{
    public function testLogin(): void
    {
        $client = $this->createApiClient();

        $this->requestJson($client, 'POST', '/api/v1/auth/login', [
            'email' => 'demo@kaizenforge.app',
            'password' => 'Demo1234!',
        ]);

        self::assertResponseIsSuccessful();

        $data = $this->responseJson($client);

        self::assertArrayHasKey('accessToken', $data);
    }
}
