<?php

declare(strict_types=1);

namespace App\Factory;

use App\Service\BinlistCacheDecorator;
use App\Service\BinlistProvider;
use App\Service\BinlistProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class BinlistProviderFactory
{
    private const string NAMESPACE = 'binlist';

    private const string BASE_URL = 'https://lookup.binlist.net';

    public function __construct(
        private string $cacheDir,
        private SerializerInterface $serializer,
    )
    {
    }

    public function createBinlistProvider(): BinlistProviderInterface
    {
        return new BinlistCacheDecorator(
            new BinlistProvider(
                new Client([
                    'base_uri' => self::BASE_URL,
                ]),
                $this->serializer,
            ),
            new FilesystemAdapter(
                namespace: self::NAMESPACE,
                directory: $this->cacheDir,
            ),
        );
    }
}
