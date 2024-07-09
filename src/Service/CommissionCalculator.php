<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Binlist\Country;
use App\Model\Transaction;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

final readonly class CommissionCalculator
{
    private const string CURRENCY_EUR = 'EUR';
    private const int SCALE = 2;
    private const string EU_COMMISSION = '0.01';
    private const string NON_EU_COMMISSION = '0.02';

    public function __construct(
        private BinlistProviderInterface $binlistProvider,
        private ExchangeRateProviderInterface $exchangeRateProvider,
    )
    {
    }

    public function calculate(Transaction $transaction): BigDecimal
    {
        $binlistResponse = $this->binlistProvider->lookup($transaction->getBin());
        $transactionAmount = $transaction->toMoney();

        $rate = $this->exchangeRateProvider->getExchangeRate($transactionAmount->getCurrency()->getCurrencyCode());

        if ($transactionAmount->getCurrency()->getCurrencyCode() === self::CURRENCY_EUR || $rate->isZero()) {
            $amount = $transactionAmount->getAmount();
        } else {
            $amount = $transactionAmount->getAmount()->dividedBy($rate, self::SCALE, RoundingMode::HALF_UP);
        }

        return $amount->multipliedBy($this->getCommissionCoef($binlistResponse->getCountry()));
    }

    private function getCommissionCoef(Country $country): BigDecimal
    {
        return BigDecimal::of($country->isEU() ? self::EU_COMMISSION : self::NON_EU_COMMISSION);
    }
}
