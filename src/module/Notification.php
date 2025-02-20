<?php
namespace Src\Module;

use Src\Interface\Printable;

final class Notification implements Printable
{
    public function __construct(public private(set) readonly string $message, public private(set) readonly string $type) {}

    public function print():true {
        echo $message;

        return true;
    }

    // Not a fan of tying up representation logic into the notification modal, but its a simple app so Ill pardon myself.
    public function printFormatted():true {
        if($this->type === 'error') {
            echo "<div style='color:red;'>".$this->message."</div>";
        } else {
            echo $this->message . '<br>';
        }

        return true;
    }

    public function __toString():string {
        return $this->message;
    }
}