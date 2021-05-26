<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', ['categories' => $categories, 'ad_list' => $ad_list]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
