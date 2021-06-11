<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('functions.php');
require_once('data.php');

$connect = mysqli_connect('localhost', 'root', 'yd7M@BJ39L@!fcK', 'yeticave');
mysqli_set_charset($connect, 'utf8');

$new_lots = 'SELECT title, c.name, starting_price, img, date_end, c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC';
$result = mysqli_query($connect, $new_lots);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

$all_categories = 'SELECT * FROM categories';
$result = mysqli_query($connect, $all_categories);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
