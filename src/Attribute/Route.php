<?php

namespace WpToolKit\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Route
{
    public function __construct() {}
}
