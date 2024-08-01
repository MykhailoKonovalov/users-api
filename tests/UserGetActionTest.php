<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserGetActionTest extends WebTestCase
{
    public function testGetUser()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/1',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ]
        );

        $this->assertJson($client->getResponse()->getContent());

        $responseBody = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('login', $responseBody);
        $this->assertArrayHasKey('phone', $responseBody);
        $this->assertArrayHasKey('pass', $responseBody);
    }

    public function testGetUserIdNotFound(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('User id not provided', $client->getResponse()->getContent());
    }

    public function testGetUserNotFound(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/100500',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ]
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('User not found', $client->getResponse()->getContent());
    }

    public function testGetUserWithDeniedAccess(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/5',
            server: [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testUser',
             ],
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'You do not have permission to view this user.',
            $client->getResponse()->getContent()
        );
    }

    public function testGetUserWithAllowedAccess(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/1',
            server: [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testUser',
             ]
        );

        $this->assertJson($client->getResponse()->getContent());

        $responseBody = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('login', $responseBody);
        $this->assertArrayHasKey('phone', $responseBody);
        $this->assertArrayHasKey('pass', $responseBody);
    }
}
