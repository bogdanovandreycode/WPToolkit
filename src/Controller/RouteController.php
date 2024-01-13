<?php

namespace WpToolKit\Controller;

use WP_REST_Request;
use WpToolKit\Interface\ParamRoureInterface;
use WpToolKit\Interface\RestRouteInterface;

abstract class RouteController implements RestRouteInterface
{
    /**
     * @param ParamRoute[] $params
     */
    public function __construct(
        public string $routeNamespace,
        public string $route,
        public array $params,
        public bool $override = false
    ) {
        add_action('rest_api_init', function () {
            register_rest_route(
                $this->routeNamespace,
                $this->route,
                [
                    'methods' => 'POST',
                    'callback' => [$this, 'callback'],
                    'permission_callback' => [$this, 'checkPermission'],
                    'args' => $this->prepareArgs($this->params),
                ],
                $this->override
            );
        });
    }

    private function prepareArgs(array $params): array
    {
        $args = [];

        foreach ($params as $param) {
            if ($param instanceof ParamRoureInterface) {
                $args = array_merge($args, $param->getArray());
            }
        }

        return $args;
    }

    abstract function callback(WP_REST_Request $request): mixed;

    abstract function checkPermission(WP_REST_Request $request): bool;
}
