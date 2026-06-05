<?php

namespace WpToolKit\Manager;

class LifecycleManager
{
    /**
     * @var callable[]
     */
    private array $activationCallbacks = [];

    /**
     * @var callable[]
     */
    private array $deactivationCallbacks = [];

    /**
     * @var callable[]
     */
    private array $uninstallCallbacks = [];

    public function onActivate(callable $callback): void
    {
        $this->activationCallbacks[] = $callback;
    }

    public function onDeactivate(callable $callback): void
    {
        $this->deactivationCallbacks[] = $callback;
    }

    public function onUninstall(callable $callback): void
    {
        $this->uninstallCallbacks[] = $callback;
    }

    public function activate(): void
    {
        foreach ($this->activationCallbacks as $callback) {
            $callback();
        }
    }

    public function deactivate(): void
    {
        foreach ($this->deactivationCallbacks as $callback) {
            $callback();
        }
    }

    public function uninstall(): void
    {
        foreach ($this->uninstallCallbacks as $callback) {
            $callback();
        }
    }
}
