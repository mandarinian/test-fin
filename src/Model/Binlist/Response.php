<?php

declare(strict_types=1);

namespace App\Model\Binlist;

final readonly class Response
{
    public function __construct(
        private Country $country,
    )
    {
    }

    public function getCountry(): Country
    {
        return $this->country;
    }
}
