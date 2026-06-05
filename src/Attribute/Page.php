<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class Page implements ControllerAttributeInterface
{
    public function __construct(
        public string $pageTitle,
        public string $menuTitle,
        public string $role,
        public string $slug,
        public int $position,
        public bool $isSubManuItem = false,
        public ?string $parentUrl = null,
        public ?string $icon = null
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'pageTitle' => $this->pageTitle,
            'menuTitle' => $this->menuTitle,
            'role' => $this->role,
            'slug' => $this->slug,
            'position' => $this->position,
            'isSubManuItem' => $this->isSubManuItem,
            'parentUrl' => $this->parentUrl,
            'icon' => $this->icon,
        ];
    }
}
