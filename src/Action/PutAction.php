<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PutAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_PUT;

    public function handle(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        $this->userService->saveUser($user, $userData);

        return new JsonResponse(['id' => $id]);
    }
}