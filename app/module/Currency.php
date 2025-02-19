<?php

namespace App\Module;

use App\Interface\CurrencyPositions;

final class Currency implements CurrencyPositions
{
    public private(set) string $sign;
    public private(set) string $space;
    public private(set) string $position;

    public function __construct(array $currency_data){
        [
            'sign' => $this->sign,
            'space' => $this->space,
            'position' => $this->position,
        ] = $currency_data;
    }

    public function formatPrice(float $price):string {
        return $this->position === 1 ? $this->space . $this->sign . sprintf("%0.2f",$price) : sprintf("%0.2f",$price). $this->sign . $this->space; // 1=after 0=before
    }
}