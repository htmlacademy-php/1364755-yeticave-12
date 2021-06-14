<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('functions.php');
require_once('data.php');
require_once('config/db.php');

if ($connect) {
    $new_lots = 'SELECT l.name AS lot_name, c.name AS category_name, starting_price, img, date_end, c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC';
    $result = mysqli_query($connect, $new_lots);
    $error = '';
} else {
    $error = mysqli_connect_error();
} if ($result) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
};

if ($connect) {
    $all_categories = 'SELECT * FROM categories';
    $result = mysqli_query($connect, $all_categories);
    $error = '';
} else {
    $error = mysqli_connect_error();
} if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
};

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots, 'error' => $error]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
