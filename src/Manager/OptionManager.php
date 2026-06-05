<?php

namespace WpToolKit\Manager;

class OptionManager
{
    public function get(string $name, mixed $default = false): mixed
    {
        return get_option($name, $default);
    }

    public function update(string $name, mixed $value, bool $autoload = true): bool
    {
        return update_option($name, $value, $autoload);
    }

    public function add(string $name, mixed $value = '', string|bool|null $deprecated = null, bool $autoload = true): bool
    {
        return add_option($name, $value, $deprecated, $autoload);
    }

    public function delete(string $name): bool
    {
        return delete_option($name);
    }

    public function getSite(string $name, mixed $default = false): mixed
    {
        return get_site_option($name, $default);
    }

    public function updateSite(string $name, mixed $value): bool
    {
        return update_site_option($name, $value);
    }

    public function deleteSite(string $name): bool
    {
        return delete_site_option($name);
    }
}
