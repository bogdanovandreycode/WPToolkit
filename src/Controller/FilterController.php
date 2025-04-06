<?php

namespace WpToolKit\Controller;

abstract class FilterController
{
    public function __construct(
        public string $hookName,
        public int $priority = 10,
        public int $acceptedArgs = 1
    ) {
        add_filter($this->hookName, [$this, 'handle'], $this->priority, $this->acceptedArgs);
    }

    abstract public function handle(...$args): mixed;
}
