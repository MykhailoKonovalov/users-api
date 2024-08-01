<?php

namespace App\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener(event: 'kernel.exception')]
readonly class ExceptionListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $this->logger->error($exception->getMessage(), $exception->getTrace());

        $response = new JsonResponse([
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}