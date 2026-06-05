<?php

namespace WpToolKit\Controller;

use WpToolKit\Interface\ContentHandlerInterface;

abstract class AdminPage implements ContentHandlerInterface
{
    protected readonly MenuController $menu;

    public function __construct(
        public string $pageTitle,
        public string $menuTitle,
        public string $role,
        public string $slug,
        public int $position,
        public bool $isSubManuItem = false,
        public ?string $parentUrl = null,
        public ?string $icon = null,
        ?MenuController $menu = null,
    ) {
        $this->menu = $menu ?? new MenuController();

        if ($isSubManuItem) {
            $this->menu->addSubItem(
                $this->parentUrl,
                $this->pageTitle,
                $this->menuTitle,
                $this->role,
                $this->slug,
                [$this, 'render'],
                $this->position
            );
        } else {
            $this->menu->addItem(
                $this->pageTitle,
                $this->menuTitle,
                $this->role,
                $this->slug,
                [$this, 'render'],
                $this->icon,
                $this->position
            );
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $currentUrl = $_SERVER['REQUEST_URI'] ?? '';

            if (
                strpos($currentUrl, 'wp-admin/') !== false &&
                strpos($currentUrl, 'page=') !== false &&
                strpos($currentUrl, $this->slug) !== false
            ) {
                $this->callback();
            }
        }
    }

    abstract public function render(): void;

    abstract public function callback(): void;
}
