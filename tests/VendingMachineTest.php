<?php 
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\VendingMachine;

final class VendingMachineTest extends TestCase
{
    public function testConstruct(): void
    {
        $currency_setting = [
            'sign' => 'лв.',
            'space' => '',
            'position' => Currency::CURRENCY_POSITION_AFTER,
        ];
        $drinks_setting = [
            'Milk' => 0.50,
            'Espresso' => 0.40,
            'Long Espresso' => 0.60,
        ];
        $setting_setting = [
            'coins' => [0.05, 0.10, 0.20, 0.50, 1,],
            'balance' => 0.00,
        ];

        $vM = new VendingMachine($currency_setting, $drinks_setting, $setting_setting);

        // $this->assertSame('John', $user->name);
        // $this->assertSame(18, $user->age);
        // $this->assertEmpty($user->favorite_movies);

        $this->assertInstanceOf(
            VendingMachine::class,
            $vM
        );
    }
}