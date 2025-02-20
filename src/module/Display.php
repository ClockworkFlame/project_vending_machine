<?php
namespace Src\Module;

use Src\Module\NotificationManager;

final class Display
{
    public static function viewDrinks(array $drinks):void {
        $notificationManager = NotificationManager::getInstance();

        $message = "<div style='font-weight:bold;'>Напитки</div>";
        
        $drinksFormatted = [];
        foreach($drinks as $drink => $cost) {
            $drinksFormatted[] = $drink . ': ' . $cost;
        }
        $message .= implode('</br>', $drinksFormatted);

        $notificationManager->setNotification($message, 'notification');
    }

    public static function printNotifications():void {
        NotificationManager::getInstance()->printAll();
    }

    public static function printErrors(int $count = 3):void {
        NotificationManager::getInstance()->printErrors();
    }
}