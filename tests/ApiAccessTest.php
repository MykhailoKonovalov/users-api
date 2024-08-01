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

        $client->request(
            Request::METHOD_PATCH,
            '/v1/api/users/1',
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }
}
