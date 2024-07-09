<?php

declare(strict_types=1);

namespace App\Service;

use Brick\Math\BigDecimal;

interface ExchangeRateProviderInterface
{
    public function getExchangeRate(string $currency): BigDecimal;
}
