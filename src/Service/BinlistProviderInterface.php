<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Binlist\Response;
use DomainException;

interface BinlistProviderInterface
{
    /**
     * @throws DomainException
     */
    public function lookup(string $bin): Response;
}
