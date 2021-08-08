<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$category_id = filter_input(INPUT_GET, 'category_id');
$lots = get_lots_by_category($connect, $category_id);
$categories = get_categories($connect);
$category_name = [];

if (count($lots)) {
    $category_name = $lots[0]['category_name'];
};

if ($current_page < 1 || ($current_page > $pages_count && $pages_count != 0)) {
    http_response_code(404);
    die();
}

$page_content = include_template('all-lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'category_name' => $category_name]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Все лоты'
]);

print($layout_content);
