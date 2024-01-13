<?php

namespace WpToolKit\Factory;

use WpToolKit\Controller\MenuController;
use WpToolKit\Controller\ScriptController;

class ServiceFactory
{
    private static $services = [];

    private static $classMap = [
        'ScriptController' => ScriptController::class,
        'MenuController' => MenuController::class,
    ];

    public static function getService(string $name): object
    {
        if (!isset(self::$classMap[$name])) {
            throw new \InvalidArgumentException("No service found for $name");
        }

        $className = self::$classMap[$name];

        if (!isset(self::$services[$name])) {
            self::$services[$name] = new $className();
        }

        return self::$services[$name];
    }
}
