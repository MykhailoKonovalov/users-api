<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserData;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UserService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function getUserById(?int $userId = null): User
    {
        if (!$userId) {
            throw new BadRequestHttpException('User id not provided', code: Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw new NotFoundHttpException('User not found', code: Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    public function saveUser(User $user, ?UserData $userData): void
    {
        if (!$userData) {
            throw new BadRequestHttpException('User cannot be empty', code: Response::HTTP_BAD_REQUEST);
        }

        $user
            ->setLogin($userData->login)
            ->setPhone($userData->phone)
            ->setPass($userData->pass);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}