<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Support\Functional\ApiFunctionalTestCase;

final class LoginTest extends ApiFunctionalTestCase
{
    public function testItLogsInWithValidCredentials(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'POST', '/api/v1/auth/login', [
            'email' => 'demo@kaizenforge.app',
            'password' => 'Demo1234!',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertSame(
            'application/json',
            $client->getResponse()->headers->get('content-type')
        );

        $data = $this->responseJson($client);

        self::assertSame(
            ['accessToken', 'tokenType', 'expiresAt', 'user'],
            array_keys($data)
        );

        self::assertIsString($data['accessToken']);
        self::assertNotSame('', $data['accessToken']);
        self::assertSame('Bearer', $data['tokenType']);

        self::assertIsArray($data['user']);
        self::assertSame(
            ['id', 'email', 'roles'],
            array_keys($data['user'])
        );

        $this->assertUuidString($data['user']['id']);
        self::assertSame('demo@kaizenforge.app', $data['user']['email']);
        self::assertSame(['ROLE_USER'], $data['user']['roles']);
        self::assertArrayNotHasKey('passwordHash', $data['user']);

        self::assertNotFalse(
            \DateTimeImmutable::createFromFormat(DATE_ATOM, $data['expiresAt'])
        );
    }

    public function testItReturnsUnauthorizedWhenPasswordIsWrong(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'POST', '/api/v1/auth/login', [
            'email' => 'demo@kaizenforge.app',
            'password' => 'wrong-password',
        ]);

        $this->assertProblemDetails(
            $client,
            401,
            'Unauthorized',
            'Invalid credentials.'
        );
    }

    public function testItReturnsUnauthorizedWhenEmailDoesNotExist(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'POST', '/api/v1/auth/login', [
            'email' => 'missing@kaizenforge.app',
            'password' => 'Demo1234!',
        ]);

        $this->assertProblemDetails(
            $client,
            401,
            'Unauthorized',
            'Invalid credentials.'
        );
    }

    public function testItReturnsValidationErrorsWhenPayloadIsInvalid(): void
    {
        $client = $this->createApiClient();

        $this->jsonRequest($client, 'POST', '/api/v1/auth/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $data = $this->assertProblemDetails(
            $client,
            422,
            'Unprocessable Content',
            'Validation failed.'
        );

        self::assertArrayHasKey('errors', $data);
        self::assertArrayHasKey('email', $data['errors']);
        self::assertArrayHasKey('password', $data['errors']);
    }
}