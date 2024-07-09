<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\ExchangeRate\Response;
use Brick\Math\BigDecimal;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final readonly class ExchangeRateApiProvider implements ExchangeRateProviderInterface
{
    public function __construct(
        private ClientInterface $client,
        private SerializerInterface $serializer,
    )
    {
    }

    public function getExchangeRate(string $currency): BigDecimal
    {
        try {
            $response = $this->serializer->deserialize(
                $this->client->request('GET', '')->getBody(),
                Response::class,
                JsonEncoder::FORMAT,
            );

            return $response->getRate($currency);
        } catch (Throwable) {
            return BigDecimal::zero();
        }
    }
}
