<?php

namespace Src\Module;

use \Src\Enum\Currency as CurrencyCase;

final class Currency
{
    public function __construct(
        public readonly string $sign,
        public readonly string $space,
        public readonly int $position,
    ){}

    public function formatPrice(float $price):string {
        return $this->position === CurrencyCase::CURRENCY_POSITION_AFTER ? $this->space . $this->sign . sprintf("%0.2f",$price) : sprintf("%0.2f",$price). $this->sign . $this->space;
    }
}