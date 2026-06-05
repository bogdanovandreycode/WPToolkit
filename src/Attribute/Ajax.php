<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class Ajax implements ControllerAttributeInterface
{
    public function __construct(
        public string $action,
        public bool $allowGuests = false
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'action' => $this->action,
            'allowGuests' => $this->allowGuests,
        ];
    }
}
