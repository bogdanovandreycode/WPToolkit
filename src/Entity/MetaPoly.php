<?php

namespace WpToolKit\Entity;

use WpToolKit\Entity\MetaPolyType;

class MetaPoly
{
    public function __construct(
        public string $name,
        public MetaPolyType $type = MetaPolyType::STRING,
        public string $title = '',
        public string $value = '',
        public string $default = '',
        public string $description = '',
        public bool $single = true,
        public bool $showInRest = true
    ) {
    }
}
