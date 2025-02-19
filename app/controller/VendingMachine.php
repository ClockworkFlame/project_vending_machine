<?php
namespace App\Controller;

use App\Module\Currency;
use App\Module\Setting;
use App\Module\Display;
use App\Module\Wallet;

final class VendingMachine
{
    private Currency $currency;
    private Setting $setting;
    private Wallet $wallet;
    private Display $display;

    // Todo: Refactor this somehow?
    // Tempted to turn all 4 into singletons, to instantiate only when necessary, but that will fuck up unit tests.
    public function __construct(array $currency_data,array $drinks_data, array $coins_data){
        $this->currency = new Currency($currency_data);
        $this->setting = new Setting($drinks_data, $coins_data);
        $this->wallet = new Wallet($this->currency, $this->setting); //Not a fan of passing down classes like this, but without a centralised DB I struggle to see another choice.
        $this->display = new Display();
    }

    public function viewDrinks():self {
        $drinks_formatted = array_map(function($cost) {
            return $this->currency->formatPrice($cost);
        },$this->setting->drinks);

        $this->display->viewDrinks($drinks_formatted);

        return $this;
    }

    public function putCoin(float $coin):self {
        try {
            $this->wallet->insertCoin($coin);

            $this->display->setNotification("Успешно поставихте ". $this->currency->formatPrice($coin) .", теĸущата Ви сума е " . $this->currency->formatPrice($this->wallet->balance));
        } catch (\Exception $e) {
            $this->display->setError($e->getMessage());
        }

        return $this;
    }

    public function buyDrink(string $name):self {
        try{
            $drink = $this->setting->getDrink($name);

            if(($payment_status = $this->wallet->makePayment($drink['cost']))) {
                $price_formatted = $this->currency->formatPrice($drink['cost']);
                $this->display->setNotification("Успешно заĸупихте '". $drink['name'] ."' от ".$price_formatted.", теĸущата Ви сума е " . $this->currency->formatPrice($this->wallet->balance));
            }
        } catch (\Exception $e) {
            $this->display->setError($e->getMessage());
        }

        return $this;
    }

    public function getCoins():self {
        try{
            $balance = $this->wallet->balance;
            $this->wallet->withdrawAll();
            $change_in_coins = $this->wallet->getChange($balance);
            $coins_string = array_map(function($coin) {
                return $this->currency->formatPrice($coin);
            }, $change_in_coins);
            $coins_counted = array_count_values($coins_string);
    
            $coins_string_formatted = [];
            foreach($coins_counted as $coin => $count) {
                $coins_string_formatted[] .= $count . 'x' . $coin;
            }
    
            $change_formatted = array_map(function($coin) {
                return $this->currency->formatPrice($coin);
            }, $change_in_coins);
    
            $this->display->setNotification("Получихте ресто ". $this->currency->formatPrice($balance) . 'в монети от: ' . implode(', ', $coins_string_formatted));
        } catch (\Exception $e) {
            $this->display->setError($e->getMessage());
        }
        
        return $this;
    }

    public function viewAmount():self {
        $this->display->setNotification("Tеĸущата Ви сума е ". $this->currency->formatPrice($this->wallet->balance));

        return $this;
    }

    public function display() {
        return $this->display;
    }
}