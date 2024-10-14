<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class ApiExceptionListener
{
    const MIME_JSON = 'application/json';

    public function onKernelException(ExceptionEvent $event): void
    {
        $acceptHeader = $event->getRequest()->headers->get('Accept');

        $exception = $event->getThrowable();

        if ($acceptHeader === self::MIME_JSON) {
            $response = new JsonResponse();

            $response->headers->set('Content-Type', self::MIME_JSON);

            match ((bool)$_ENV['APP_DEBUG']) {
                true => $response->setContent($this->devExceptionToJson($exception)),
                false => $response->setContent($this->prodExceptionToJson($exception)),
                default => false
            };

            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $event->setResponse($response);
        }
    }

    public function devExceptionToJson(\Throwable $exception): string
    {
        return json_encode(
            [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }

    public function prodExceptionToJson(\Throwable $exception): string
    {
        return json_encode(
            [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]
        );
    }


}