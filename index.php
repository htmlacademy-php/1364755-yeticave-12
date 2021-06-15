<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('functions.php');
require_once('data.php');
require_once('config/db.php');

$new_lots = 'SELECT l.name AS lot_name, c.name AS category_name, starting_price, img, date_end, c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC';
$lots = get_array($connect, $new_lots);

$all_categories = 'SELECT * FROM categories';
$categories = get_array($connect, $all_categories);

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
