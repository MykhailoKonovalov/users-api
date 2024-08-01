<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_POST;

    public function handle(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = new User();

        $this->userService->saveUser($user, $userData);

        return new JsonResponse(
            [
                'id'    => $user->getId(),
                'login' => $user->getLogin(),
                'phone' => $user->getPhone(),
                'pass'  => $user->getPass(),
            ], Response::HTTP_CREATED
        );
    }
}