<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public ?string $email = null,

        #[Assert\NotBlank]
        public ?string $password = null,
    ) {
    }
}
