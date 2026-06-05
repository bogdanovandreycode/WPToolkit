<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;

#[Attribute(Attribute::TARGET_CLASS)]
final class Cron implements ControllerAttributeInterface
{
    public function __construct(
        public string $hookName,
        public string $recurrence,
        public ?int $startTimestamp = null
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'hookName' => $this->hookName,
            'recurrence' => $this->recurrence,
            'startTimestamp' => $this->startTimestamp,
        ];
    }
}
