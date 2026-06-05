<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class Shortcode implements ControllerAttributeInterface
{
    /**
     * @param array<string, mixed> $atts
     */
    public function __construct(
        public string $name,
        public array $atts = []
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'name' => $this->name,
            'atts' => $this->atts,
        ];
    }
}
