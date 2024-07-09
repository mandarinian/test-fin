<?php

declare(strict_types=1);

namespace App\Factory;

use App\Service\ExchangeRateProviderInterface;
use App\Service\ExchangeRateApiProvider;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class ExchangeRateProviderFactory
{
    private const string BASE_URL = 'https://api.exchangeratesapi.io/latest/';
    private const string FREE_EXCHANGE_PROVIDER_BASE_URL = 'https://open.er-api.com/v6/latest/USD';

    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    public function createExchangeRateProvider(): ExchangeRateProviderInterface
    {
        return new ExchangeRateApiProvider(
            new Client([
                'base_uri' => self::BASE_URL,
            ]),
            $this->serializer,
        );
    }

    public function createFreeExchangeRateProvider(): ExchangeRateProviderInterface
    {
        return new ExchangeRateApiProvider(
            new Client([
                'base_uri' => self::FREE_EXCHANGE_PROVIDER_BASE_URL,
            ]),
            $this->serializer,
        );
    }
}
