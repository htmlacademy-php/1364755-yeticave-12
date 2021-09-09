<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$categories = get_categories($connect);
$search = filter_input(INPUT_GET, 'search');
$current_page = $_GET['page'] ?? 1;

$lots = [];
$page_items = 9;
$pages = [];
$pages_count = 0;

$error = validate_length($search, 1, 15);
if ($error) {
    $lots = get_lots($connect);
    $page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
} elseif ($search) {
    $offset = ($current_page - 1) * $page_items;
    $data = [$search . '*', $page_items, $offset];
    $lots = search_by_lots($connect, $data);
    $items_count = count($lots);
    $pages_count = ceil($items_count / $page_items);

    if ($current_page < 1 || ($current_page > $pages_count && $pages_count != 0)) {
        http_response_code(404);
        die();
    }

    if ($pages_count > 0) {
        $pages = range(1, $pages_count);
    }
}

$page_content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search,
    'pages' => $pages,
    'current_page' => $current_page
]);

$layout_content = include_template('layout.php', [
    'error' => $error,
    'content' => $page_content,
    'search' => $search,
    'categories' => $categories,
    'title' => 'Результаты поиска'
]);

print($layout_content);
