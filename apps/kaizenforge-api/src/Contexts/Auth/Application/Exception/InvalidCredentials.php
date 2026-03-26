<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Exception;

use App\Shared\Application\Exception\ApplicationException;

final class InvalidCredentials extends ApplicationException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials.');
    }
}
