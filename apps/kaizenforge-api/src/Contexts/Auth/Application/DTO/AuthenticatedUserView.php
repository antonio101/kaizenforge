<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\DTO;

final readonly class AuthenticatedUserView
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        public string $id,
        public string $email,
        public array $roles,
    ) {
    }
}
