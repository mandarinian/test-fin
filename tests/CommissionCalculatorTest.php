<?php

declare(strict_types=1);

namespace tests;

use App\Model\Binlist\Country;
use App\Model\Binlist\Response;
use App\Model\Transaction;
use App\Service\BinlistProviderInterface;
use App\Service\CommissionCalculator;
use App\Service\ExchangeRateProviderInterface;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use DomainException;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class CommissionCalculatorTest extends TestCase
{
    #[TestWith(['45717360', '100.00', 'EUR', 'DK', '0.923422', '1.00'])]
    #[TestWith(['516793', '50.00', 'USD', 'LT', '1', '0.50'])]
    #[TestWith(['45417360', '10000.00', 'JPY', 'JP', '160.788117', '1.24'])]
    #[TestWith(['45417360', '10000.00', 'JPY', 'JP', '0', '200.00'])]
    #[TestWith(['4745030', '2000.00', 'GBP', 'LT', '0.780188', '25.63'])]
    public function testCalculateForEurCurrency(
        string $bin,
        string $amount,
        string $currency,
        string $country,
        string $rate,
        string $result,
    ): void
    {
        $transaction = new Transaction($bin, $amount, $currency);

        $binlistProvider = self::createStub(BinlistProviderInterface::class);
        $binlistProvider
            ->method('lookup')
            ->willReturn(new Response(new Country($country)));
        $exchangeRateProvider = self::createStub(ExchangeRateProviderInterface::class);
        $exchangeRateProvider
            ->method('getExchangeRate')
            ->willReturn(BigDecimal::of($rate));

        $commissionCalculator = new CommissionCalculator(
            $binlistProvider,
            $exchangeRateProvider,
        );

        self::assertTrue(
            $commissionCalculator
                ->calculate($transaction)
                ->toScale(2, RoundingMode::HALF_UP)
                ->isEqualTo(BigDecimal::of($result)),
        );
    }

    public function testCalculateWithBinlistError(): void
    {
        $transaction = new Transaction('41417360', '130.00', 'USD');

        $binlistProvider = self::createStub(BinlistProviderInterface::class);
        $binlistProvider
            ->method('lookup')
            ->willThrowException(new DomainException(sprintf('Failed to fetch info for bin "%s".', '41417360')));
        $exchangeRateProvider = self::createStub(ExchangeRateProviderInterface::class);
        $exchangeRateProvider
            ->method('getExchangeRate')
            ->willReturn(BigDecimal::of('0'));

        $commissionCalculator = new CommissionCalculator(
            $binlistProvider,
            $exchangeRateProvider,
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Failed to fetch info for bin "41417360".');
        $commissionCalculator->calculate($transaction);
    }
}
