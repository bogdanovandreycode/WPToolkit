<?php

namespace WpToolKit\Entity;

class Post
{
    /**
     * @var string[] $supports
     */
    public function __construct(
        public string $name,
        public string $title,
        public string $icon,
        public string $role,
        public array $supports,
        public bool $public = true,
        public bool $rest = true,
        public int $position = 0
    ) {
    }

    public function getUrl(): string
    {
        return '/edit.php?post_type=' . $this->name;
    }
}
