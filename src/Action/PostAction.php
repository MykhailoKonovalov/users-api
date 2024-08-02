<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use App\Entity\User;
use App\Security\ApiPermission\UsersApiVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PostAction extends Action
{
    public const ACTION_METHOD = Request::METHOD_POST;

    public function execute(?UserData $userData = null, ?int $id = null): JsonResponse
    {
        $user = new User();

        if (!$this->authorizationChecker->isGranted(UsersApiVoter::CREATE, $user)) {
            throw new AccessDeniedHttpException('You do not have permission to create user.');
        }

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