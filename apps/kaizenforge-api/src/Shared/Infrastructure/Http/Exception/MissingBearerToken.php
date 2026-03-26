<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class MissingBearerToken extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('Missing bearer token.');
    }
}
