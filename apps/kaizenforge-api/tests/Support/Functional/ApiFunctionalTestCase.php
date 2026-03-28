<?php

declare(strict_types=1);

namespace App\Tests\Support\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

abstract class ApiFunctionalTestCase extends WebTestCase
{
    protected function createApiClient(): KernelBrowser
    {
        return static::createClient();
    }

    protected function requestJson(KernelBrowser $client, string $method, string $uri, array $payload = [], array $headers = []): void
    {
        $server = array_merge([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], $headers);

        $content = $payload === [] ? null : json_encode($payload, JSON_THROW_ON_ERROR);

        $client->request($method, $uri, [], [], $server, $content);
    }

    protected function responseJson(KernelBrowser $client): array
    {
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($data);

        return $data;
    }

    protected function assertUuidString(mixed $value): void
    {
        self::assertIsString($value);
        self::assertTrue(Uuid::isValid($value));
    }
}
