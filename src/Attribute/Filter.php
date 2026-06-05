<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class Filter implements ControllerAttributeInterface
{
    public function __construct(
        public string $hookName,
        public int $priority = 10,
        public int $acceptedArgs = 1
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'hookName' => $this->hookName,
            'priority' => $this->priority,
            'acceptedArgs' => $this->acceptedArgs,
        ];
    }
}
