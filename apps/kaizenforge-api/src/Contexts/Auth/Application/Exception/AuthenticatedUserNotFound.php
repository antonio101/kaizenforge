<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Exception;

final class AuthenticatedUserNotFound extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Authenticated user not found.');
    }
}