<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Support\Functional\ApiFunctionalTestCase;
use App\Tests\Support\Functional\Auth\AuthenticatesApiRequests;

final class MeTest extends ApiFunctionalTestCase
{
    use AuthenticatesApiRequests;

    public function testMe(): void
    {
        $client = $this->createApiClient();
        $token = $this->authenticate($client);

        $this->requestJson($client, 'GET', '/api/v1/auth/me', [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token
        ]);

        self::assertResponseIsSuccessful();
    }
}
