<?php

declare(strict_types=1);

namespace App\ClassObjects;

class UserContext
{
    public function __construct(
        private readonly string $ip,
        private readonly string $userAgent,
    ) {
        //
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}
