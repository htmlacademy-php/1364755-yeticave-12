<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$categories = get_categories($connect);
$user_id = $_SESSION['user']['user_id'];
$my_bets = get_bets_by_user_id($connect, [$user_id]);

$page_content = include_template('my-bets.php', [
    'categories' => $categories,
    'my_bets' => $my_bets
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Мои ставки'
]);

print($layout_content);
