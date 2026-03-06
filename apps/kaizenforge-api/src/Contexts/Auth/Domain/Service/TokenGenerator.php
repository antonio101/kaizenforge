<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Domain\Service;

interface TokenGenerator
{
    public function generatePlainToken(): string;
}
