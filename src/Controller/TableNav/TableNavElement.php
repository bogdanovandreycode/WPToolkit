<?php

namespace WpToolKit\Controller\TableNav;

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
