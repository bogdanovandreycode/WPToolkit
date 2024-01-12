<?php

namespace WpToolKit\Entity;

class Post
{
    public function __construct(
        private string $name,
        private string $title,
        private string $icon,
        private string $role,

        /**
         * @var string[]
         */
        private array $supports,

        private int $position = 0
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $value): void
    {
        $this->title = $value;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $value): void
    {
        $this->icon = $value;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $value): void
    {
        $this->role = $value;
    }

    public function getSupports(): array
    {
        return $this->supports;
    }

    public function setSupports(array $value): void
    {
        $this->supports = $value;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $value): void
    {
        $this->position = $value;
    }

    public function getUrl(): string
    {
        return '/edit.php?post_type=' . $this->name;
    }
}
