<?php
namespace App\Module;

use \App\Module\Currency;
use \App\Module\Setting;

final class Wallet
{
    public private(set) float $balance = 0.0;

    public function __construct(private Currency $currency, private Setting $setting){
    }

    public function insertCoin(float $amount):void {
        if(!$this->isValidCoin($amount)) {
            $accepted_coins_formatted = array_map(function($coin) {
                return $this->currency->formatPrice($coin);
            }, $this->setting->accepted_coins);

            throw new \Exception('Автомата приема монети от: '. implode(', ', $accepted_coins_formatted));
        }
        $this->balance += $amount;
    }

    public function makePayment(float $cost):bool {
        if($this->balance < $cost){
            throw new \Exception('Недостатъчна наличност.');
            return false;
        } else {
            $this->balance = ( ( floor($this->balance * 100) - floor($cost * 100) ) / 100 );
            return true;
        }
    }

    // Formats change to return in coin types
    public function getChange(float $amount):array {
        $remaining = $amount;
        $change_in_coins = [];
        $accepted_coins_sorted = $this->setting->accepted_coins;
        rsort($accepted_coins_sorted); //Sort from highest to lowers, so we dont get 0.05 0.05 0.05 0.05 ...

        // Im sure there's a prettier way, but I like my simple foreach.
        foreach($accepted_coins_sorted as $coin) {
            // Loop through the coin in case the amount is a multiple of it.
            while($remaining >= $coin) {
                $change_in_coins[] = $coin;

                // https://stackoverflow.com/questions/17210787/php-float-calculation-error-when-subtracting
                $remaining = ( ( floor($remaining * 100) - floor($coin * 100) ) / 100 );
            }

            if($remaining === 0) {
                break;
            }
        }

        if(empty($change_in_coins)) {
            throw new \Exception('Няма ресто');
        }
        
        return $change_in_coins;
    }

    public function withdrawAll():void {
        $this->balance = 0.0;
    }

    private function isValidCoin(float $amount):bool {
        return in_array(sprintf("%0.2f",$amount), $this->setting->accepted_coins);
    }
}