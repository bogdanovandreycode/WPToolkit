<?php

namespace WpToolKit\Controller;

class MenuController
{
    public function addItem(
        string $pageTitle,
        string $menuTitle,
        string $role,
        string $url,
        mixed $renderFunction,
        string $icon,
        int $position
    ): void {
        add_action('admin_menu', function () use (
            $pageTitle,
            $menuTitle,
            $role,
            $url,
            $renderFunction,
            $icon,
            $position
        ) {
            add_menu_page(
                $pageTitle,
                $menuTitle,
                $role,
                $url,
                $renderFunction,
                $icon,
                $position
            );
        });
    }

    public function addSubItem(
        string $parentUrl,
        string $pageTitle,
        string $menuTitle,
        string $role,
        string $url,
        mixed $renderFunction,
        int $position
    ): void {
        add_action('admin_menu', function () use (
            $parentUrl,
            $pageTitle,
            $menuTitle,
            $role,
            $url,
            $renderFunction,
            $position
        ) {
            add_submenu_page(
                $parentUrl,
                $pageTitle,
                $menuTitle,
                $role,
                $url,
                $renderFunction,
                $position
            );
        });
    }
}
