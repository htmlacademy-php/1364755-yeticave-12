<?php

require_once('functions.php');
require_once('data.php');
require_once('config/db.php');

$categories = get_categories($connect);

$page_content = include_template('add-lot.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
