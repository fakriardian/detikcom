<?php
$variables = [
    'DB_HOST' => 'localhost',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
    'DB_NAME' => 'test',
    'DB_PORT' => 3306,
];

foreach ($variables as $key => $value) {
    putenv("$key=$value");
}
