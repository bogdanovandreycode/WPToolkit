<?php

namespace WpToolKit\Manager;

use WP_User;
use WpToolKit\Interface\UserFieldInterface;

class UserFieldManager
{
    /** @var array<string, UserFieldInterface[]> */
    private array $fields = [];

    public function __construct()
    {
        add_action('show_user_profile', [$this, 'renderFields']);
        add_action('edit_user_profile', [$this, 'renderFields']);

        add_action('personal_options_update', [$this, 'saveFields']);
        add_action('edit_user_profile_update', [$this, 'saveFields']);
    }

    public function addField(UserFieldInterface $field, int $priority = 10): void
    {
        $this->fields[$priority][] = $field;
    }

    public function renderFields(WP_User $user): void
    {
        ksort($this->fields); // Сортируем по приоритету
        foreach ($this->fields as $fieldGroup) {
            foreach ($fieldGroup as $field) {
                $field->render($user);
            }
        }
    }

    public function saveFields(int $userId): void
    {
        ksort($this->fields); // Тоже желательно сортировать
        foreach ($this->fields as $fieldGroup) {
            foreach ($fieldGroup as $field) {
                $field->save($userId);
            }
        }
    }
}
