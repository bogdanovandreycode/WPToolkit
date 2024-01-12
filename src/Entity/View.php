<?php

namespace WpToolKit\Entity;

final class View
{
    /**
     * Undocumented function
     *
     * @param <string, mixed> $variables
     */
    public function __construct(
        public string $name,
        public string $path,
        private array $variables
    ) {
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function addVariable(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    public function deleteVariable(string $name): void
    {
        unset($this->variables[$name]);
    }
}
