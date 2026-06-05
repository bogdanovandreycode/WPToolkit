<?php

namespace WpToolKit\Controller;

abstract class AjaxController
{
    public function __construct(
        public string $action,
        public bool $allowGuests = false
    ) {
        add_action("wp_ajax_{$this->action}", [$this, 'handle']);

        if ($this->allowGuests) {
            add_action("wp_ajax_nopriv_{$this->action}", [$this, 'handle']);
        }
    }

    abstract public function handle(): void;
}
