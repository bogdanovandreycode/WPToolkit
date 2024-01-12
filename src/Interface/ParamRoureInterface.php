<?php

namespace WpToolKit\Interface;

use WP_Error;

interface ParamRoureInterface
{
    public function getArray(): array;

    function validate($param, $request, $key): bool|WP_Error;

    function sanitize($param, $request, $key): mixed;
}
