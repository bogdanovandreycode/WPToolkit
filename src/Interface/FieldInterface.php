<?php

namespace WpToolKit\Interface;

interface FieldInterface
{
    public function renderLabel(): string;
    public function renderField(): string;
}
