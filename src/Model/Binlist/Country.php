<?php

declare(strict_types=1);

namespace App\Model\Binlist;

final readonly class Country
{
    private const array EU = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct(
        private string $alpha2,
    )
    {
    }

    public function isEU(): bool
    {
        return in_array($this->alpha2, self::EU, true);
    }
}
