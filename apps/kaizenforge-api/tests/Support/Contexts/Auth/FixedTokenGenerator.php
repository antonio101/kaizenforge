<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Domain\Service\TokenGenerator;

final class FixedTokenGenerator implements TokenGenerator
{
    /**
     * @var list<string>
     */
    private array $tokens;

    public int $calls = 0;

    /**
     * @param list<string> $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = array_values($tokens);
    }

    public function generatePlainToken(): string
    {
        ++$this->calls;

        if ($this->tokens === []) {
            throw new \LogicException('No more fixed tokens available.');
        }

        return array_shift($this->tokens);
    }
}
