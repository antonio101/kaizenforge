<?php

declare(strict_types=1);

namespace App\Tests\Support\Contexts\Auth;

use App\Contexts\Auth\Application\Port\PasswordVerifier;
use App\Contexts\Auth\Domain\Model\User;

final class SpyPasswordVerifier implements PasswordVerifier
{
    public int $calls = 0;
    public ?User $lastUser = null;
    public ?string $lastPlainPassword = null;

    /**
     * @var \Closure(User, string): bool
     */
    private \Closure $resolver;

    public function __construct(bool|\Closure $result)
    {
        $this->resolver = $result instanceof \Closure
            ? $result
            : static fn (): bool => $result;
    }

    public function verify(User $user, string $plainPassword): bool
    {
        ++$this->calls;
        $this->lastUser = $user;
        $this->lastPlainPassword = $plainPassword;

        return ($this->resolver)($user, $plainPassword);
    }
}
