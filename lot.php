<?php

require_once('functions.php');
require_once('data.php');
require_once('config/db.php');

$lot = get_lot_by_id($connect);
$categories = get_categories($connect);

$page_content = include_template('lot_main.php', ['categories' => $categories, 'lot' => $lot]);
$layout_content = include_template('lot_layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
