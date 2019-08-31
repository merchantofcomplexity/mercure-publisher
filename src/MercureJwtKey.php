<?php
declare(strict_types=1);

namespace MerchantOfComplexity\MercurePublisher;

use Assert\Assertion;

final class MercureJwtKey
{
    private string $jwt;

    public function __construct(string $jwt)
    {
        Assertion::notBlank($jwt, "Mercure jwt can not be empty");

        $this->jwt = $jwt;
    }

    public function __invoke(): string
    {
        return $this->jwt;
    }
}
