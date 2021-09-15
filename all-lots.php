<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$category_id = filter_input(INPUT_GET, 'category_id');
$categories = get_categories($connect);
$category_name = [];
$current_page = $_GET['page'] ?? 1;
$page_items = 9;
$pages = [];
$pages_count = 0;

$offset = ($current_page - 1) * $page_items;
$data = [$category_id, $page_items, $offset];
$lots = get_lots_by_category($connect, $data);
$items_count = count($lots);
$pages_count = ceil($items_count / $page_items);

if ($current_page < 1 || ($current_page > $pages_count && $pages_count != 0)) {
    http_response_code(404);
    die();
}

if ($pages_count > 0) {
    $pages = range(1, $pages_count);
}

if ($items_count) {
    $category_name = $lots[0]['category_name'];
};

$page_content = include_template('all-lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'category_name' => $category_name,
    'pages' => $pages,
    'current_page' => $current_page
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Все лоты'
]);

print($layout_content);
