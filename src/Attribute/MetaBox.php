<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Entity\MetaBoxContext;
use WpToolKit\Entity\MetaBoxPriority;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class MetaBox implements ControllerAttributeInterface
{
    public function __construct(
        public string $id,
        public string $title,
        public string $postName,
        public MetaBoxContext $context = MetaBoxContext::ADVANCED,
        public MetaBoxPriority $priority = MetaBoxPriority::DEFAULT
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'postName' => $this->postName,
            'context' => $this->context,
            'priority' => $this->priority,
        ];
    }
}
