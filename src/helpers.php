<?php

use WpToolKit\Factory\ServiceFactory;

if (!function_exists('app')) {
    function app(?string $abstract = null, array $parameters = []): mixed
    {
        return ServiceFactory::app($abstract, $parameters);
    }
}
