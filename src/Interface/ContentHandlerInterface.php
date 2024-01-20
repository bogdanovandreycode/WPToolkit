<?php

namespace WpToolKit\Interface;

interface ContentHandlerInterface
{
    public function render(): void;
    public function callback(): void;
}
