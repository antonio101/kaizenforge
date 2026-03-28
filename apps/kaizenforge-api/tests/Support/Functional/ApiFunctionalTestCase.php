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

    protected function jsonRequest(
        KernelBrowser $client,
        string $method,
        string $uri,
        array $payload = [],
        array $server = []
    ): void {
        $client->jsonRequest($method, $uri, $payload, $server);
    }

    protected function responseJson(KernelBrowser $client): array
    {
        $content = $client->getResponse()->getContent();

        self::assertIsString($content);

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($data);

        return $data;
    }

    protected function assertUuidString(mixed $value): void
    {
        self::assertIsString($value);
        self::assertTrue(Uuid::isValid($value));
    }

    protected function assertProblemDetails(
        KernelBrowser $client,
        int $status,
        string $title,
        string $detail
    ): array {
        self::assertResponseStatusCodeSame($status);
        self::assertSame(
            'application/problem+json',
            $client->getResponse()->headers->get('content-type')
        );

        $data = $this->responseJson($client);

        self::assertSame($title, $data['title']);
        self::assertSame($status, $data['status']);
        self::assertSame($detail, $data['detail']);
        self::assertArrayHasKey('type', $data);
        self::assertArrayHasKey('instance', $data);

        return $data;
    }
}