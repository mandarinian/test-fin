<?php

declare(strict_types=1);

namespace App\Model;

use Brick\Money\Money;

final readonly class Transaction
{
    public function __construct(
        private string $bin,
        private string $amount,
        private string $currency,
    )
    {
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function toMoney(): Money
    {
        return Money::of($this->amount, $this->currency);
    }
}
