<?php
namespace App\Module;

final class Display
{
    // Todo: Make a Notifications factory class.
    private array $notifications;

    public function setError(string $message):void {
        $this->notifications[] = ['message' => $message . "</div>", 'type' => 'error'];
    }

    public function setNotification(string $message):void {
        $this->notifications[] = ['message' => $message . '<br>', 'type' => 'notification'];
    }

    public function viewDrinks(array $drinks):void {
        $message = "<div style='font-weight:bold;'>Напитки</div>";
        
        foreach($drinks as $drink => $cost) {
            $message .= $drink . ': ' . $cost . '<br>';
        }
        $this->notifications[] = ['message' => $message, 'type' => 'notification'];
    }

    public function all() {
        foreach($this->notifications as $notification) {
            $this->printNotification($notification);
        }
    }

    public function printErrors(int $count = 3):bool {
        if(empty(($errors = $this->getErrors()))) {
            return false;
        }

        echo "<div style='font-weight:bold;'>Last ".$count." errors</div>";

        for($i = 0; $i < $count; $i++) {
            echo $this->printNotification($errors[$i]);
        }

        return true;
    }

    private function getErrors():array {
        return array_values(array_filter($this->notifications, function($notif) {
            return $notif['type'] === 'error';
        }));
    }

    private function printNotification(array $notif):void {
        if($notif['type'] === 'error') {
            echo "<div style='color:red;'>".$notif['message']."</div>";
        } else {
            echo $notif['message'];
        }
    }
}