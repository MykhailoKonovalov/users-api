<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAccessTest extends WebTestCase
{
    public function testWrongMethod(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_PATCH, '/v1/api/users/1',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
                'CONTENT_TYPE'       => 'application/json'
            ]
        );

        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }

    public function testWrongCredential(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/1',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer abcd',
                'CONTENT_TYPE'       => 'application/json'
            ]
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testUnauthorized(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/1',
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }
}
