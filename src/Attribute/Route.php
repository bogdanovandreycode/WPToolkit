<?php

namespace WpToolKit\Attribute;

use Attribute;
use WpToolKit\Factory\ServiceFactory;
use WpToolKit\Interface\ParamRoureInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class Route implements ControllerAttributeInterface
{
    /**
     * @param array<int, class-string<ParamRoureInterface>|ParamRoureInterface> $params
     */
    public function __construct(
        public string $routeNamespace,
        public string $route,
        public array $params = [],
        public bool $override = false,
        public string $methods = 'POST'
    ) {
    }

    public function toParameters(ServiceFactory $serviceFactory): array
    {
        return [
            'routeNamespace' => $this->routeNamespace,
            'route' => $this->route,
            'params' => array_map(
                function (mixed $param) use ($serviceFactory): mixed {
                    if (
                        is_string($param)
                        && is_subclass_of($param, ParamRoureInterface::class)
                    ) {
                        return $serviceFactory->make($param);
                    }

                    return $param;
                },
                $this->params
            ),
            'override' => $this->override,
            'methods' => $this->methods,
        ];
    }
}
