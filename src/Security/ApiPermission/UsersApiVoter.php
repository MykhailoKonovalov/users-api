<?php

namespace App\Security\ApiPermission;

use App\Entity\User;
use App\Security\Authentication\TokenAuthenticator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UsersApiVoter extends Voter
{
    public const CREATE = 'create';

    public const EDIT   = 'edit';

    public const VIEW   = 'view';

    public const DELETE = 'delete';

    public function __construct(private readonly RequestStack $requestStack) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        $userToken = $this->requestStack
            ->getCurrentRequest()
            ->headers
            ->get(TokenAuthenticator::AUTHORIZATION_HEADER);

        if (
            preg_match(TokenAuthenticator::TOKEN_REGEX, $userToken, $matches)
            && TokenAuthenticator::ADMIN_TOKEN === $matches[1]
        ) {
            return true;
        }

        return match ($attribute) {
            self::VIEW,
            self::EDIT   => $currentUser->getId() === $subject->getId(),
            self::CREATE => true,
            default      => false,
        };
    }
}