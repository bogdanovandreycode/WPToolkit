<?php

namespace WpToolKit\Factory;

use WpToolKit\Controller\MenuController;
use WpToolKit\Controller\ScriptController;

class ServiceFactory
{
    private static $services = [];

    public static function getService(string $name): object
    {
        if (!isset(self::$services[$name])) {
            self::$services[$name] = match ($name) {
                ScriptController::class => new ScriptController(),
                MenuController::class => new MenuController(),
            };
        }

        return self::$services[$name];
    }
}
