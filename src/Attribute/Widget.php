<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class Widget implements ControllerAttributeInterface
{
    public function __construct(
        public string $idBase,
        public string $name,
        public string $description = ''
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'idBase' => $this->idBase,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
