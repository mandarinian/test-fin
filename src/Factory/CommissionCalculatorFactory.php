<?php

declare(strict_types=1);

namespace App\Factory;

use App\Enum\ExchangeRateProviderEnum;
use App\Service\CommissionCalculator;

final readonly class CommissionCalculatorFactory
{
    public function __construct(
        private BinlistProviderFactory $binlistProviderFactory,
        private ExchangeRateProviderFactory $exchangeRateProviderFactory,
    )
    {
    }

    public function createCommissionCalculator(
        ExchangeRateProviderEnum $exchangeRateProviderEnum = ExchangeRateProviderEnum::PAID,
    ): CommissionCalculator
    {
        return new CommissionCalculator(
            $this->binlistProviderFactory->createBinlistProvider(),
            match ($exchangeRateProviderEnum) {
                ExchangeRateProviderEnum::PAID => $this->exchangeRateProviderFactory->createExchangeRateProvider(),
                ExchangeRateProviderEnum::FREE => $this->exchangeRateProviderFactory->createFreeExchangeRateProvider(),
            },
        );
    }
}
