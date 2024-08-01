<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_DELETE;

    public function handle(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        $this->userService->delete($user);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}