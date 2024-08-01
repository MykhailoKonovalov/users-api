<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    public const AUTHORIZATION_HEADER = 'Authorization';

    public const  TOKEN_REGEX          = '/^Bearer\s(\S+)$/';

    public const  ADMIN_TOKEN          = 'testAdmin';

    public const  USER_TOKEN           = 'testUser';

    public function __construct(private readonly UserRepository $userRepository) {}

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::AUTHORIZATION_HEADER);
    }

    public function authenticate(Request $request): Passport
    {
        $authorizationToken = $request->headers->get(self::AUTHORIZATION_HEADER);

        if (!$authorizationToken) {
            throw new AuthenticationException('Missing authorization token');
        }

        if (!preg_match(self::TOKEN_REGEX, $authorizationToken, $matches)) {
            throw new AuthenticationException('Invalid authorization token');
        }

        $token = $matches[1];

        if ($token == self::ADMIN_TOKEN) {
            $userIdentifier = 'admin';
        } elseif ($token == self::USER_TOKEN) {
            $userIdentifier = 'login_1';
        } else {
            throw new BadCredentialsException('Incorrect user token');
        }

        return new SelfValidatingPassport(
            new UserBadge(
                $userIdentifier, function (string $userIdentifier): ?UserInterface {
                    return $this->userRepository->findOneBy(['login' => $userIdentifier]);
                },
            ),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }
}
