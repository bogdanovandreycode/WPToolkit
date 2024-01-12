<?php

namespace WpToolKit\Entity;

use WpToolKit\Entity\MetaPolyType;

class MetaPoly
{
    public function __construct(
        private string $name,
        private MetaPolyType $type = MetaPolyType::STRING,
        private string $title = '',
        private string $value = '',
        private string $default = '',
        private string $description = '',
        private bool $single = true,
        private bool $showInRest = true
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): MetaPolyType
    {
        return $this->type;
    }

    public function setType(MetaPolyType $type): void
    {
        $this->type = $type;
    }

    public function getDefault(): string
    {
        return $this->default;
    }

    public function setDefault(string $default): void
    {
        $this->default = $default;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isSingle(): bool
    {
        return $this->single;
    }

    public function setSingle(bool $single): void
    {
        $this->single = $single;
    }

    public function isShowInRest(): bool
    {
        return $this->showInRest;
    }

    public function setShowInRest(bool $showInRest): void
    {
        $this->showInRest = $showInRest;
    }
}
