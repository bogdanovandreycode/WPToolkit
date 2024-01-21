<?php

namespace WpToolKit\Controller;

use WP_Error;
use WpToolKit\Interface\ParamRoureInterface;

abstract class ParamRoute implements ParamRoureInterface
{
    public function __construct(
        public string $name,
        public mixed $default = null,
        public ?bool $required = null,
    ) {
    }

    public function getArray(): array
    {
        return [
            $this->name => [
                'default' => $this->default,
                'required' => $this->required,
                'validate_callback' => [$this, 'validate'],
                'sanitize_callback' => [$this, 'sanitize'],
            ]
        ];
    }

    abstract function validate($param, $request, $key): bool|WP_Error;

    abstract function sanitize($param, $request, $key): mixed;
}
