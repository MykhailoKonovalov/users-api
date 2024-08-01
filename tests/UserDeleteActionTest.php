<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDeleteActionTest extends WebTestCase
{
    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/v1/api/users/2',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUserIdNotFound(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/v1/api/users',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('User id not provided', $client->getResponse()->getContent());
    }

    public function testDeleteUserNotFound(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/v1/api/users/100500',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('User not found', $client->getResponse()->getContent());
    }

    public function testDeleteUserWithDeniedAccess(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/v1/api/users/10',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testUser',
            ],
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'You do not have permission to delete this user.',
            $client->getResponse()->getContent()
        );
    }
}
