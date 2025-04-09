<?php

return [
    "enabled" => env('TELEGRAM_ENABLED', false),
    "token" => env('TELEGRAM_TOKEN', ''),
    "dbhost" => env('TELEGRAM_DB_HOST', 'localhost'),
    "dblogin" => env('TELEGRAM_DB_LOGIN', 'root'),
    "dbpassword" => env('TELEGRAM_DB_PASSWORD', ''),
    "dbname" => env('TELEGRAM_DB_NAME', ''),
    "dbquery" => env('TELEGRAM_DB_QUERY', 'SELECT `telegram_id` FROM `users` WHERE `phone_number` LIKE :entity OR `billing_uid`=:uid'),
    "userid_field" => env('TELEGRAM_DB_USERID_FIELD', 'telegram_id'),
    "priority" => env('TELEGRAM_PRIORITY', 1),
    "prefix" => env('TELEGRAM_PREFIX', "any"),
    "tags" => env('TELEGRAM_TAGS', '#telegram, #tg'),
    "skip_tag" => env('TELEGRAM_SKIP_TAG', '#skipTG'),
    "default" => env('TELEGRAM_DEFAULT', false),
    "devmode" => env('TELEGRAM_DEVMODE', false),
];
