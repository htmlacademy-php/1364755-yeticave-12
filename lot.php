<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$lot = get_lot_by_id($connect);
$categories = get_categories($connect);

$page_content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot]);

if (empty($lot)) {
    $page_content = include_template('404.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $lot['name']
]);

print($layout_content);
