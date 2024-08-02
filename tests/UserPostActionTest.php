<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPostActionTest extends WebTestCase
{
    public function testPostUser(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/v1/api/users',
            server :     [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
            content:     json_encode([
                'login' => 'newuser',
                'phone' => '12345678',
                'pass'  => 'pass1234'
            ]),
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $responseContent = $client->getResponse()->getContent();

        $this->assertJson($responseContent);

        $response = json_decode($responseContent, true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('login', $response);
        $this->assertArrayHasKey('phone', $response);
        $this->assertArrayHasKey('pass', $response);
    }

    public function testPostUserInvalidRequestData(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/v1/api/users',
            server :      [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
            content: json_encode([
                 'login' => 'newuser',
                 'phone' => '1234567890',
                 'pass'  => 'password123'
            ])
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
    }

    public function testPostUserMissingRequestData(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/v1/api/users',
            server : [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
            content: json_encode(
                 [
                     'login' => 'newuser',
                     'phone' => '11100100',
                 ])
        );

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(0, $responseBody['code']);
    }

    public function testPostUserDuplicatedData(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/v1/api/users',
            server :     [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
            content:     json_encode([
                 'login' => 'newuser',
                 'phone' => '12345678',
                 'pass'  => 'pass1234'
             ])
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
    }
}
