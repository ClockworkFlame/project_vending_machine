<?php
namespace Src\Module;

use Interface\Printable;

final class Notification extends Printable
{
    public private(set) readonly string $type;

    public function __construct(public private(set) readonly string $message, public private(set) readonly string $type)

    public function print():true {

    }

    public function __toString():string {
        return $message;
    }
}