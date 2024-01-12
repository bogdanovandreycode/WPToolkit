<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\View;

class ViewLoader
{
    /**
     * @var View[] Array of views
     */
    private static array $views = [];

    public static function load(string $name): void
    {
        if (array_key_exists($name, self::$views)) {
            $variables = self::$views[$name]->getVariables();
            extract($variables);
            ob_start();
            require self::$views[$name]->path;
            echo ob_get_clean();
        }
    }

    public static function add(View $view): void
    {
        if (!in_array($view, self::$views, true)) {
            self::$views[$view->name] = $view;
        }
    }

    public static function delete(View $view): void
    {
        $key = array_search($view, self::$views, true);

        if ($key !== false) {
            unset(self::$views[$key]);
        }
    }

    /**
     * Gets a view by name.
     *
     * @return View|null The view or null if not found.
     */
    public static function getView(string $name): ?View
    {
        return self::$views[$name] ?? null;
    }
}
