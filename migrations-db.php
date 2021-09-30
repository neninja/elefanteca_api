<?php

require_once __DIR__.'/bootstrap/app.php';

return [
    'driver'    => env('DB_CONNECTION_DOCTRINE'),
    'host'      => env('DB_HOST'),
    'dbname'    => env('DB_DATABASE'),
    'user'      => env('DB_USERNAME'),
    'password'  => env('DB_PASSWORD'),
];
