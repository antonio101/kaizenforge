<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Controller;

use App\Contexts\Auth\Application\Command\LoginCommand;
use App\Contexts\Auth\Application\Handler\LoginHandler;
use App\Contexts\Auth\Domain\Exception\InvalidCredentials;
use App\Contexts\Auth\Infrastructure\Http\Request\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class LoginController extends AbstractController
{
    #[Route('/api/v1/auth/login', name: 'kf_auth_login', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        LoginHandler $handler,
    ): JsonResponse {
        /** @var LoginRequest $payload */
        $payload = $serializer->deserialize(
            $request->getContent(),
            LoginRequest::class,
            'json'
        );

        $violations = $validator->validate($payload);

        if (\count($violations) > 0) {
            $errors = [];

            /** @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $errors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $result = $handler(new LoginCommand(
                email: (string) $payload->email,
                password: (string) $payload->password,
            ));
        } catch (InvalidCredentials) {
            return $this->json([
                'message' => 'Invalid credentials.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($result, Response::HTTP_OK);
    }
}
