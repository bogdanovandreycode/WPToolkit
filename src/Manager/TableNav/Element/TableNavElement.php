<?php

namespace WpToolKit\Manager\TableNav\Element;

use WpToolKit\Interface\TableNav\TableNavElementInterface;

class TableNavElement implements TableNavElementInterface
{
    public function __construct(
        private string $html
    ) {}

    public function render(): string
    {
        return $this->html;
    }
}
