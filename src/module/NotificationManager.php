<?php 
namespace Src\Module;

use Src\Module\Notification;
use App\Singleton;

/**
 * Singleton to manage notifications throughout the project
 */
final class NotificationManager extends Singleton
{
    private array $notifications = [];

    public function setNotification(string $message, string $type = 'notification'):bool {
        $notification = new Notification($message, $type);

        if(!($notification instanceof Notification)) {
            return false;
        }

        $this->notifications[] = $notification;
        return true;
    }

    public function printAll():bool {
        if(empty($this->notifications)) {
            return false;
        }

        foreach($this->notifications as $notification) {
            $notification->printFormatted();
        }

        return true;
    }

    // Not a fan of tying up representation logic into the notification modal, but its a simple app so Ill pardon myself.
    public function printErrors(int $count = 3):bool {
        if(empty(($errors = $this->getErrors()))) {
            return false;
        }

        echo "<div style='font-weight:bold;'>Last ".$count." errors</div>";

        for($i = 0; $i < $count; $i++) {
            $errors[$i]->printFormatted();
        }

        return true;
    }

    private function getErrors():array {
        return array_values(array_filter($this->notifications, function($notif) {
            return $notif->type === 'error';
        }));
    }
}