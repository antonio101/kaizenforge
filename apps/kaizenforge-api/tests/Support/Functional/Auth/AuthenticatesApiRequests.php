<?php

declare(strict_types=1);

namespace App\Tests\Support\Functional\Auth;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait AuthenticatesApiRequests
{
    protected function authenticate(KernelBrowser $client): string
    {
        $client->request('POST', '/api/v1/auth/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'demo@kaizenforge.app',
            'password' => 'Demo1234!'
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);

        return $data['accessToken'];
    }
}
