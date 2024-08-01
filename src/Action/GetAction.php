<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_GET;

    public function handle(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        return new JsonResponse(
            [
                'login' => $user->getLogin(),
                'phone' => $user->getPhone(),
                'pass'  => $user->getPass(),
            ],
        );
    }
}