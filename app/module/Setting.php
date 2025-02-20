<?php
namespace App\Module;

final class Setting
{   
    public private(set) array $drinks;
    public private(set) array $accepted_coins = [];

    public function __construct(array $drinks_data = [], array $coins_data = []){
        if(!empty($drinks_data)) {
            $this->addDrinks($drinks_data);
        }

        if(!empty($coins_data)) {
            $this->addCoins($coins_data['coins']);
        }
    }

    public function addDrinks(array $drinks):void {
        foreach($drinks as $name => $cost) {
            if(empty($name) || empty($cost)) {
                throw new \Exception('Empty drink values');
            }

            $this->drinks[$name] = $cost;
        }
    }

    public function addCoins(array $coins):void {
        foreach($coins as $value) {
            $value = is_integer($value) ? floatval($value) : $value;

            if(is_float($value) && $value > 0) {
                $this->accepted_coins[] = $value;
            } else {
                throw new \Exception('Invalid coin values');
            }
        }

        array_unique($this->accepted_coins); // Filter out duplicate coins
    }

    public function getDrink(string $name):array {
        if(array_key_exists($name, $this->drinks)) {
            return ['name' => $name, 'cost' => $this->drinks[$name]];
        } else {
            throw new \Exception('Исĸаният продуĸт не е намерен');
        }
    }

    public function deleteCoin(float $coin):void {
        if(($key = array_search($coin, $this->accepted_coins)) !== false) {
            unset($this->accepted_coins[$key]);
        } else {
            throw new \Exception('Монетата не съществува');
        }
    }

    public function deleteDrink(string $name):void {
        if(($key = array_search($name, $this->drinks)) !== false) {
            unset($this->drinks[$name]);
        } else {
            throw new \Exception('Напитката не съществува');
        }
    }
}