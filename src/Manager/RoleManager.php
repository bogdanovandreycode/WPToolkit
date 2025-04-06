<?php

namespace WpToolKit\Manager;

use Symfony\Component\Yaml\Yaml;

class RoleManager
{
    /**
     * @var array<string, array<string, bool>>
     */
    private array $roles = [];

    public function addRole(string $role, string $displayName, array $capabilities): void
    {
        if (!get_role($role)) {
            add_role($role, $displayName, $capabilities);
        }
        $this->roles[$role] = $capabilities;
    }

    public function removeRole(string $role): void
    {
        if (get_role($role)) {
            remove_role($role);
        }
        unset($this->roles[$role]);
    }

    public function removeAllRoles(): void
    {
        foreach (array_keys($this->roles) as $role) {
            $this->removeRole($role);
        }
    }

    /**
     * @return array<string, array<string, bool>>
     */
    public function getAll(): array
    {
        return $this->roles;
    }

    public function loadFromYaml(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("YAML file not found: $path");
        }

        $parsed = Yaml::parseFile($path);

        foreach ($parsed as $role => $data) {
            $this->addRole(
                $role,
                $data['display_name'] ?? ucfirst($role),
                $data['capabilities'] ?? []
            );
        }
    }
}
