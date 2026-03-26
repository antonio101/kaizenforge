<?php

declare(strict_types=1);

namespace App\Contexts\Auth\Infrastructure\Configuration;

use App\Contexts\Auth\Application\Port\AccessTokenTtlProvider;

final readonly class ConfiguredAccessTokenTtlProvider implements AccessTokenTtlProvider
{
    public function __construct(
        private string $spec,
    ) {
    }

    public function get(): \DateInterval
    {
        try {
            return new \DateInterval($this->spec);
        } catch (\Exception $exception) {
            throw new \LogicException(
                'Invalid access token TTL: ' . $this->spec,
                previous: $exception,
            );
        }
    }
}
