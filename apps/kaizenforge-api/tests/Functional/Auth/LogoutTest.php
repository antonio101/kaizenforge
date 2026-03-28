<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Support\Functional\ApiFunctionalTestCase;
use App\Tests\Support\Functional\Auth\AuthenticatesApiRequests;

final class LogoutTest extends ApiFunctionalTestCase
{
    use AuthenticatesApiRequests;

    public function testLogout(): void
    {
        $client = $this->createApiClient();
        $token = $this->authenticate($client);

        $this->requestJson($client, 'POST', '/api/v1/auth/logout', [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token
        ]);

        self::assertResponseIsSuccessful();
    }
}
