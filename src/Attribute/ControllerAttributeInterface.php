<?php

namespace WpToolKit\Attribute;

use WpToolKit\Factory\ServiceFactory;

interface ControllerAttributeInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toParameters(ServiceFactory $serviceFactory): array;
}
