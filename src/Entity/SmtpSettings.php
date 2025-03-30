<?php

namespace WPToolkit\Entity;

class SmtpSettings
{
    public function __construct(
        public string $host,
        public bool $auth,
        public string $username,
        public string $password,
        public int $port,
        public string $secure,
        public int $debugMode = 0,
        public string $debugFileOutput = 'smtp.log',
    ) {}
}
