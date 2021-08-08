<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$categories = get_categories($connect);

$page_content = include_template('my-bets.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Мои ставки'
]);

print($layout_content);
