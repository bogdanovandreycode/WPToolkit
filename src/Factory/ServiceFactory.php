<?php

namespace WpToolKit\Factory;

use WpToolKit\Controller\MenuController;
use WpToolKit\Controller\ScriptController;

class ServiceFactory
{
    private static $services = [
        ScriptController::class => new ScriptController(),
        MenuController::class => new MenuController()
    ];

    public static function getService(string $name): object
    {
        return self::$services[$name];
    }
}
