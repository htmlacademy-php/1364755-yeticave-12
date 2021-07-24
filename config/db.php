<?php

session_start();

$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'yd7M@BJ39L@!fcK',
    'database' => 'yeticave'
];

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, 'utf8');
