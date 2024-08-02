<?php

declare(strict_types=1);

namespace App\Service;

use App\Action\Action;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ActionResolver
{
    /** @var Action[] $actions */
    private array $actions = [];

    public function addAction(Action $action): void
    {
        $this->actions[] = $action;
    }

    public function resolve(string $method): Action
    {
        foreach ($this->actions as $action) {
            if ($action->supports($method)) {
                return $action;
            }
        }

        throw new MethodNotAllowedHttpException(
            ['GET', 'POST', 'PUT', 'DELETE'],
            'Method Not Allowed',
            code: Response::HTTP_METHOD_NOT_ALLOWED
        );
    }
}