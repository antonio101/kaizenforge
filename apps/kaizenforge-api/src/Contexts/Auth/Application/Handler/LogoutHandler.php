<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Application\Handler;

use App\Contexts\Auth\Application\Command\LogoutCommand;
use App\Contexts\Auth\Domain\Repository\AccessTokenRepository;
use App\Contexts\Auth\Domain\ValueObject\TokenHash;

final readonly class LogoutHandler
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function __invoke(LogoutCommand $command): void
    {
        $this->accessTokenRepository->revokeByHash(
            TokenHash::fromPlainToken($command->plainAccessToken)
        );
    }
}
