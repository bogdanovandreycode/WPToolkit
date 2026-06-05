<?php

namespace WpToolKit\Manager;

final class AdminMenuManager
{
    /** @var array<array{target: string, movable: string}> */
    private array $movedMenuRules = [];

    public function init(): void
    {
        add_action('admin_init', [$this, 'applyMovedMenus']);
    }

    public function moveMenuAfterTarget(string $targetMenu, string $menuToMove): void
    {
        $this->movedMenuRules[] = [
            'target' => $targetMenu,
            'movable' => $menuToMove,
        ];
    }

    public function applyMovedMenus(): void
    {
        global $menu;

        if (empty($menu)) {
            return;
        }

        foreach ($this->movedMenuRules as $rule) {
            $targetPosition = $this->getPositionByName($rule['target'], $menu);
            $movablePosition = $this->getPositionByName($rule['movable'], $menu);

            if ($targetPosition === null || $movablePosition === null) {
                continue;
            }

            $movableItem = $menu[$movablePosition];
            unset($menu[$movablePosition]);
            array_splice($menu, $targetPosition + 1, 0, [$movableItem]);
        }
    }

    private function getPositionByName(string $menuName, array $menu): ?int
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
