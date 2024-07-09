<?php

declare(strict_types=1);

namespace App\Model\ExchangeRate;

use Brick\Math\BigDecimal;
use DomainException;

final readonly class Response
{
    public function __construct(
        private array $rates,
    )
    {
    }

    public function getRate(string $currency): BigDecimal
    {
        $rate = $this->rates[$currency] ?? null;

        if ($rate === null) {
            throw new DomainException(sprintf('Failed to fetch rate for currency "%s".', $currency));
        }

        return BigDecimal::of($rate);
    }
}
