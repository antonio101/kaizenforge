<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Error;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private HttpExceptionMapper $exceptionMapper,
        private ApiProblemResponseFactory $responseFactory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->supports($request)) {
            return;
        }

        $throwable = $event->getThrowable();
        $statusCode = $this->exceptionMapper->statusCodeFor($throwable);
        $validationException = $this->findValidationFailedException($throwable);

        $detail = $validationException instanceof ValidationFailedException
            ? 'Validation failed.'
            : $this->exceptionMapper->detailFor($throwable, $statusCode);

        $extra = $validationException instanceof ValidationFailedException
            ? ['errors' => $this->normalizeViolations($validationException)]
            : [];

        $headers = $throwable instanceof HttpExceptionInterface
            ? $throwable->getHeaders()
            : [];

        $event->setResponse($this->responseFactory->create(
            request: $request,
            status: $statusCode,
            title: $this->exceptionMapper->titleFor($statusCode),
            detail: $detail,
            extra: $extra,
            headers: $headers,
        ));
    }

    private function supports(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api');
    }

    private function findValidationFailedException(\Throwable $throwable): ?ValidationFailedException
    {
        for ($exception = $throwable; $exception !== null; $exception = $exception->getPrevious()) {
            if ($exception instanceof ValidationFailedException) {
                return $exception;
            }
        }

        return null;
    }

    /**
     * @return array<string, list<string>>
     */
    private function normalizeViolations(ValidationFailedException $exception): array
    {
        $errors = [];

        foreach ($exception->getViolations() as $violation) {
            $field = $violation->getPropertyPath() !== ''
                ? $violation->getPropertyPath()
                : '_';

            $errors[$field][] = $violation->getMessage();
        }

        ksort($errors);

        return $errors;
    }
}
