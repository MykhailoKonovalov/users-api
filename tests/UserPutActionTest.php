<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPutActionTest extends WebTestCase
{
    public function testPutUser(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_PUT, '/v1/api/users/3',
            server : [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
             ],
            content: json_encode(
                 [
                     'login' => 'test',
                     'phone' => '11100100',
                     'pass'  => 'pass1233',
                 ],
             ),
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(3, $responseBody['id']);
    }

    public function testPutUserInvalidRequestData(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_PUT, '/v1/api/users/1',
            server : [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
             ],
            content: json_encode(
                 [
                     'login' => 'test',
                     'phone' => '11100100',
                     'pass'  => 'pass12345',
                 ],
             ),
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
    }

    public function testPutUserMissingRequestData(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_PUT, '/v1/api/users/1',
            server : [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
             ],
            content: json_encode(
                 [
                     'login' => 'test',
                     'phone' => '11100100',
                 ],
             ),
        );

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
    }

    public function testPutUserIdNotFound(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_PUT, '/v1/api/users',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('User id not provided', $client->getResponse()->getContent());
    }

    public function testPutUserNotFound(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_PUT, '/v1/api/users/100500',
            server: [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer testAdmin',
            ],
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('User not found', $client->getResponse()->getContent());
    }

    public function testPutUserWithDeniedAccess(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_PUT, '/v1/api/users/3',
            server : [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testUser',
             ],
            content: json_encode(
                 [
                     'login' => 'test',
                     'phone' => '11100100',
                     'pass'  => 'pass1233',
                 ],
             ),
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'You do not have permission to edit this user.',
            $client->getResponse()->getContent(),
        );
    }

    public function testPutUserWithAllowedAccess(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_PUT, '/v1/api/users/1',
            server : [
                 'CONTENT_TYPE'       => 'application/json',
                 'HTTP_AUTHORIZATION' => 'Bearer testUser',
             ],
            content: json_encode(
                 [
                     'login' => 'login_1',
                     'phone' => '11100100',
                     'pass'  => 'pass1233',
                 ],
             ),
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseBody = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, $responseBody['id']);
    }
}
