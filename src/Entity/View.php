<?php

namespace WpToolKit\Entity;

final class View
{
    /**
     * @param array<string, mixed> $variables
     */
    public function __construct(
        public string $name,
        public string $path,
        private array $variables
    ) {
    }

    /**
     * @return array<string, mixed>
     */
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
