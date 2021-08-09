<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$lot_id = filter_input(INPUT_GET, 'id');
$lot = get_lot_by_id($connect, $lot_id);
$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = filter_input_array(INPUT_POST, ['sum' => FILTER_VALIDATE_INT]);
    $data['user_id'] = $_SESSION['user']['user_id'];
    $data['lot_id'] = $lot_id;
    $errors = [];
    $bet = add_bet($connect, $data);
}

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
