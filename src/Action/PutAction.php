<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use App\Security\ApiPermission\UsersApiVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PutAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_PUT;

    public function handle(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$this->authorizationChecker->isGranted(UsersApiVoter::EDIT, $user)) {
            throw new AccessDeniedHttpException('You do not have permission to edit this user.');
        }

        $this->userService->saveUser($user, $userData);

        return new JsonResponse(['id' => $id]);
    }
}