<?php

namespace WpToolKit\Manager;

class NonceManager
{
    public function create(string|int $action = -1): string
    {
        return wp_create_nonce($action);
    }

    public function verify(string $nonce, string|int $action = -1): int|false
    {
        return wp_verify_nonce($nonce, $action);
    }

    public function field(
        string|int $action = -1,
        string $name = '_wpnonce',
        bool $referer = true,
        bool $display = true
    ): string {
        return wp_nonce_field($action, $name, $referer, $display);
    }

    public function url(string $actionUrl, string|int $action = -1, string $name = '_wpnonce'): string
    {
        return wp_nonce_url($actionUrl, $action, $name);
    }

    public function checkAdminReferer(string|int $action = -1, string $queryArg = '_wpnonce'): int|false
    {
        return check_admin_referer($action, $queryArg);
    }

    public function checkAjaxReferer(
        string|int $action = -1,
        string|false $queryArg = false,
        bool $stop = true
    ): int|false {
        return check_ajax_referer($action, $queryArg, $stop);
    }
}
