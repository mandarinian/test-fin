<?php

declare(strict_types=1);

use App\Enum\ExchangeRateProviderEnum;
use App\Factory\BinlistProviderFactory;
use App\Factory\CommissionCalculatorFactory;
use App\Factory\ExchangeRateProviderFactory;
use App\Factory\SerializerFactory;
use App\Model\Transaction;
use Brick\Math\RoundingMode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

require_once __DIR__ . '/vendor/autoload.php';

$file = new SplFileObject($argv[1]);
$serializerFactory = new SerializerFactory();
$serializer = $serializerFactory->createSerializer();
$commissionCalculatorFactory = new CommissionCalculatorFactory(
    new BinlistProviderFactory(__DIR__ . '/cache', $serializer),
    new ExchangeRateProviderFactory($serializer),
);
$commissionCalculator = $commissionCalculatorFactory->createCommissionCalculator(ExchangeRateProviderEnum::PAID);

while (!$file->eof()) {
    try {
        $transaction = $serializer->deserialize($file->fgets(), Transaction::class, JsonEncoder::FORMAT);
    } catch (ExceptionInterface) {
        continue;
    }

    try {
        $commission = $commissionCalculator->calculate($transaction);

        echo $commission->toScale(2, RoundingMode::HALF_UP) . "\n";
    } catch (DomainException $exception) {
        echo $exception->getMessage() . "\n";
    }
}

$file = null;
