<?php
namespace Src\Module;

use Src\Module\NotificationManager;

final class Display
{
    private NotificationManager $notificationManager;

    public function __construct() {
        $this->notificationManager = NotificationManager::getInstance();
    }

    public function setError(string $message):void {
        $this->notificationManager->setNotification($message, 'error');
    }

    public function setNotification(string $message):void {
        $this->notificationManager->setNotification($message, 'notification');
    }

    public function viewDrinks(array $drinks):void {
        $message = "<div style='font-weight:bold;'>Напитки</div>";
        
        $drinksFormatted = [];
        foreach($drinks as $drink => $cost) {
            $drinksFormatted[] = $drink . ': ' . $cost;
        }
        $message .= implode('</br>', $drinksFormatted);

        $this->notificationManager->setNotification($message, 'notification');
    }

    public function allNotifications():void {
        $this->notificationManager->printAll();
    }

    public function printErrors(int $count = 3):void {
        $this->notificationManager->printErrors();
    }
}