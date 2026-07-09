<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class WpBakeryElement implements ControllerAttributeInterface
{
    /**
     * @param array<int, array<string, mixed>> $params
     */
    public function __construct(
        public string $base,
        public string $name,
        public string $category = '',
        public string $description = '',
        public string $icon = '',
        public array $params = [],
        public bool $designOptions = true,
        public string $designParamName = 'wptk_design'
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'base' => $this->base,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'icon' => $this->icon,
            'params' => $this->params,
            'designOptions' => $this->designOptions,
            'designParamName' => $this->designParamName,
        ];
    }
}
