<?php

declare(strict_types=1);

namespace App\Tests\Support\Functional\Auth;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait AuthenticatesApiRequests
{
    protected function authenticate(
        KernelBrowser $client,
        string $email = 'demo@kaizenforge.app',
        string $password = 'Demo1234!'
    ): string {
        $this->jsonRequest($client, 'POST', '/api/v1/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        self::assertResponseIsSuccessful();

        $data = $this->responseJson($client);

        self::assertArrayHasKey('accessToken', $data);
        self::assertIsString($data['accessToken']);
        self::assertNotSame('', $data['accessToken']);

        return $data['accessToken'];
    }
}
