<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$lots = get_lots($connect);
$categories = get_categories($connect);

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная'
]);

print($layout_content);
