<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Binlist\Response;
use DomainException;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final readonly class BinlistProvider implements BinlistProviderInterface
{
    public function __construct(
        private ClientInterface $client,
        private SerializerInterface $serializer,
    )
    {
    }

    public function lookup(string $bin): Response
    {
        try {
            return $this->serializer->deserialize(
                (string) $this->client->request('GET', $bin)->getBody(),
                Response::class,
                JsonEncoder::FORMAT,
            );
        } catch (Throwable) {
            throw new DomainException(sprintf('Failed to fetch info for bin "%s".', $bin));
        }
    }
}
