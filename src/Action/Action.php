<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\UserData;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Action
{
    public const ACTION_METHOD = "";

    public function __construct(protected readonly UserService $userService) {}

    public function canHandle(string $method): bool
    {
        return $method === static::ACTION_METHOD;
    }

    abstract public function handle(?UserData $userData = null, ?int $id = null): JsonResponse;
}