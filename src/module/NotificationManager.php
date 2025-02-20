<?php 
namespace Src\Module;

use Src\Module\Notification;

/**
 * Singleton to manage notifications throughout the project
 */
final class NotificationManager
{
    private static $instances = [];
    private array $notifications = [];

    private function __construct() {}

    private function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * The method you use to get the Singleton's instance.
     */
    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }
}