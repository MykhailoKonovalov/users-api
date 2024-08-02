<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use App\Security\ApiPermission\UsersApiVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DeleteAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_DELETE;

    public function execute(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$this->authorizationChecker->isGranted(UsersApiVoter::DELETE, $user)) {
            throw new AccessDeniedHttpException('You do not have permission to delete this user.');
        }

        $this->userService->deleteUser($user);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}