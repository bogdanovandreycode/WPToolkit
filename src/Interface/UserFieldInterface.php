<?php

namespace WpToolKit\Interface;

interface UserFieldInterface
{
    public function render(\WP_User $user): void;
    public function save(int $userId): void;
}
