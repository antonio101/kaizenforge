<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Exception;

use App\Shared\Application\Exception\ApplicationException;

final class Unauthenticated extends ApplicationException
{
    public function __construct()
    {
        parent::__construct('Authentication is required to access this resource.');
    }
}
