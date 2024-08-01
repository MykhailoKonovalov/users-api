<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserData;
use App\Service\ActionResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/v1/api/users/{id?}', requirements: ['id' => '\d+'])]
class UserController extends AbstractController
{
    public function __construct(private readonly ActionResolver $actionProvider) {}

    public function __invoke(
        Request $request,
        #[MapRequestPayload] ?UserData $userData = null,
        ?int $id = null,
    ) : JsonResponse {
        $action = $this->actionProvider->resolve($request->getMethod());

        return $action->handle($userData, $id);
    }
}
