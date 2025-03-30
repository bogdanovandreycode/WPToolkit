<?php

namespace WpToolKit\Manager;

final class AdminMenuManager
{
    /** @var array<array{target: string, movable: string}> */
    private static $ruleMovedMenus = [];

    public static function init()
    {
        add_action('admin_init', [__CLASS__, 'applyMovedMenus']);
    }

    public static function moveMenuAfterTarget(string $targetMenu, string $menuToMove): void
    {
        self::$ruleMovedMenus[] = ['target' => $targetMenu, 'movable' => $menuToMove];
    }

    public static function applyMovedMenus(): void
    {
        global $menu;

        if (empty($menu)) {
            return;
        }

        foreach (self::$ruleMovedMenus as $rule) {
            $targetPosition = self::getPositionByName($rule['target'], $menu);
            $movablePosition = self::getPositionByName($rule['movable'], $menu);

            if ($targetPosition === null || $movablePosition === null) {
                continue;
            }

            $movableItem = $menu[$movablePosition];
            unset($menu[$movablePosition]);
            array_splice($menu, $targetPosition + 1, 0, [$movableItem]);
        }
    }

    private static function getPositionByName(string $menuName, array $menu): ?int
    {
        if (empty($menu)) {
            return null;
        }

        foreach ($menu as $position => $menuItem) {
            if (isset($menuItem[0]) && $menuItem[0] === $menuName) {
                return $position;
            }
        }

        return null;
    }
}
