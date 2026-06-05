<?php

namespace WpToolKit\Manager;

class TransientManager
{
    public function get(string $key): mixed
    {
        return get_transient($key);
    }

    public function set(string $key, mixed $value, int $expiration = 0): bool
    {
        return set_transient($key, $value, $expiration);
    }

    public function delete(string $key): bool
    {
        return delete_transient($key);
    }

    public function getSite(string $key): mixed
    {
        return get_site_transient($key);
    }

    public function setSite(string $key, mixed $value, int $expiration = 0): bool
    {
        return set_site_transient($key, $value, $expiration);
    }

    public function deleteSite(string $key): bool
    {
        return delete_site_transient($key);
    }
}
