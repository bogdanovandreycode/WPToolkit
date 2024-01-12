<?php

namespace WpToolKit\Entity;

enum ScriptType: string
{
    case FRONT = 'wp_enqueue_scripts';
    case ADMIN = 'admin_enqueue_scripts';
    case LOGIN = 'login_enqueue_scripts';
}
