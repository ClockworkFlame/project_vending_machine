<?php

use Src\Controller\VendingMachine;
use Src\Module\Display;
use Src\Enum\Currency;

// Setup autoloader for namespaces
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    include implode('/', $parts) . '.php';
});

$vendingMachine = new VendingMachine([
    'sign' => 'лв.',
    'space' => '',
    'position' => Currency::CURRENCY_POSITION_AFTER->value,
],
[
    'Milk' => 0.50,
    'Espresso' => 0.40,
    'Long Espresso' => 0.60,
],
[
    'coins' => [0.05, 0.10, 0.20, 0.50, 1,],
    'balance' => 0.00,
]);

$vendingMachine
    ->buyDrink( 'espresso' )
    ->buyDrink( 'Espresso' )
    ->viewDrinks()
    ->putCoin( 2 )
    ->putCoin( 1 )
    ->buyDrink( 'Espresso' )
    ->getCoins()
    ->viewAmount()
    ->getCoins();

Display::printNotifications();
echo '<br>';
Display::printErrors();