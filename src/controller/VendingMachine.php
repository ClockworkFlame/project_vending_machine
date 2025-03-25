<?php
namespace Src\Controller;

use Src\Module\Currency;
use Src\Module\Setting;
use Src\Module\Display;
use Src\Module\Wallet;
use Src\Module\NotificationManager;

final class VendingMachine
{
    private readonly Currency $currency;
    private readonly Setting $setting;
    private readonly Wallet $wallet;

    // Todo: Refactor this somehow?
    public function __construct(array $currency_data,array $drinks_data, array $coins_data){
        $this->currency = new Currency($currency_data['sign'], $currency_data['space'], $currency_data['position']);
        $this->setting = new Setting($drinks_data, $coins_data);
        $this->wallet = new Wallet($this->currency, $this->setting); //Not a fan of high coupling like this, but idk.
    }

    public function viewDrinks():self {
        $drinks_formatted = array_map(function($cost) {
            return $this->currency->formatPrice($cost);
        },$this->setting->drinks);

        Display::viewDrinks($drinks_formatted);

        return $this;
    }

    public function putCoin(float $coin):self {
        try {
            $this->wallet->insertCoin($coin);

            NotificationManager::getInstance()->setNotification("Успешно поставихте ". $this->currency->formatPrice($coin) .", теĸущата Ви сума е " . $this->currency->formatPrice($this->wallet->balance), 'notification');
        } catch (\Exception $e) {
            NotificationManager::getInstance()->setNotification($e->getMessage(), 'error');
        }

        return $this;
    }

    public function buyDrink(string $name):self {
        try{
            $drink = $this->setting->getDrink($name);

            if(($payment_status = $this->wallet->makePayment($drink['cost']))) {
                $price_formatted = $this->currency->formatPrice($drink['cost']);
                NotificationManager::getInstance()->setNotification("Успешно заĸупихте '". $drink['name'] ."' от ".$price_formatted.", теĸущата Ви сума е " . $this->currency->formatPrice($this->wallet->balance), 'notification');
            }
        } catch (\Exception $e) {
            NotificationManager::getInstance()->setNotification($e->getMessage(), 'error');
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
                $coins_string_formatted[] = $count . 'x' . $coin;
            }
    
            $change_formatted = array_map(function($coin) {
                return $this->currency->formatPrice($coin);
            }, $change_in_coins);
    
            NotificationManager::getInstance()->setNotification("Получихте ресто ". $this->currency->formatPrice($balance) . 'в монети от: ' . implode(', ', $coins_string_formatted), 'notification');
        } catch (\Exception $e) {
            NotificationManager::getInstance()->setNotification($e->getMessage(), 'error');
        }
        
        return $this;
    }

    public function viewAmount():self {
        NotificationManager::getInstance()->setNotification("Tеĸущата Ви сума е ". $this->currency->formatPrice($this->wallet->balance), 'notification');

        return $this;
    }
}