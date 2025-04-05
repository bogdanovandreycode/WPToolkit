<?php

namespace WpToolKit\Manager;

use WP_User;
use WpToolKit\Interface\UserFieldInterface;

class UserFieldManager
{
    private array $fields = [];

    public function __construct()
    {
        add_action('show_user_profile', [$this, 'renderFields']);
        add_action('edit_user_profile', [$this, 'renderFields']);

        add_action('personal_options_update', [$this, 'saveFields']);
        add_action('edit_user_profile_update', [$this, 'saveFields']);
    }

    public function addField(UserFieldInterface $field): void
    {
        $this->fields[] = $field;
    }

    public function renderFields(WP_User $user): void
    {
        foreach ($this->fields as $field) {
            $field->render($user);
        }
    }

    public function saveFields(int $userId): void
    {
        foreach ($this->fields as $field) {
            $field->save($userId);
        }
    }
}
