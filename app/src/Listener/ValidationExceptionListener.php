<?php

namespace App\Listener;

use App\Exception\ValidationException;
use App\Model\Error\ErrorResponse;
use App\Model\Error\ErrorValidationDetails;
use App\Model\Error\ValidationErrorList;
use App\Model\Error\ValidationErrorResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationExceptionListener
{
    public function __construct(
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if (!($throwable instanceof ValidationException)) {
            return;
        }

        $data = $this->serializer->serialize(
            new ValidationErrorResponse(
                $throwable->getMessage(),
                $this->formatErrors($throwable->getViolationList())
            ),
            JsonEncoder::FORMAT
        );

        $event->setResponse(
            new JsonResponse(
                $data,
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json'],
                true
            )
        );
    }

    private function formatErrors(ConstraintViolationListInterface $violations): array
    {
        $errorList = new ValidationErrorList();

        foreach ($violations as $violation) {
            $errorList->addViolation(
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }

        return $errorList->getViolations();
    }

}