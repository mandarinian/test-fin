<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Binlist\Response;
use Psr\Cache\CacheItemPoolInterface;
use Throwable;

final readonly class BinlistCacheDecorator implements BinlistProviderInterface
{
    public function __construct(
        private BinlistProviderInterface $decoratedProvider,
        private CacheItemPoolInterface $cacheItemPool,
    )
    {
    }

    public function lookup(string $bin): Response
    {
        $cache = $this->cacheItemPool->getItem($bin);

        try {
            $response = $this->decoratedProvider->lookup($bin);

            $this->cacheItemPool->save($cache->set($response));

            return $response;
        } catch (Throwable $e) {
            if ($cache->isHit()) {
                return $cache->get();
            }

            throw $e;
        }
    }
}
