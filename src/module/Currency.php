<?php

namespace Src\Module;

use Src\Interface\CurrencyPositions;

final class Currency
{
    const CURRENCY_POSITION_BEFORE = 0;
    const CURRENCY_POSITION_AFTER = 1;

    public private(set) readonly string $sign;
    public private(set) readonly string $space;
    public private(set) readonly string $position;

    public function __construct(array $currency_data){
        [
            'sign' => $this->sign,
            'space' => $this->space,
            'position' => $this->position,
        ] = $currency_data;
    }

    public function formatPrice(float $price):string {
        return $this->position === self::CURRENCY_POSITION_AFTER ? $this->space . $this->sign . sprintf("%0.2f",$price) : sprintf("%0.2f",$price). $this->sign . $this->space; // 1=after 0=before
    }
}