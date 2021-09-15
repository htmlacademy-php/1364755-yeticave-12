<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$lot_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$lot = get_lot_by_id($connect, $lot_id);
$categories = get_categories($connect);
$lot_bets = get_bets_by_lot_id($connect, [$lot_id]);
$current_price = $lot_bets ? $lot_bets[0]['sum'] : $lot['starting_price'];
$min_bet = $current_price + $lot['bet_step'];
$bets_history = get_bets_history_by_lot_id($connect, [$lot_id]);
$errors = [];
$value = [];

$page_content = include_template('404.php', ['categories' => $categories]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['sum'] = filter_input(INPUT_POST, 'sum', FILTER_VALIDATE_INT);
    $data['user_id'] = $_SESSION['user']['user_id'];
    $data['lot_id'] = $lot_id;
    $value = $data['sum'];

    if ($value < $min_bet) {
        $errors = 'Введите целое число, которое больше либо равно минимальной ставке';
    }

    if ($lot['user_id'] == $data['user_id']) {
        $errors = 'Вы не можете сделать ставку к своему лоту';
    }

    if (empty($errors)) {
        add_bet($connect, $data);
        $bet_id = mysqli_insert_id($connect);
        add_bet_id_to_user($connect, [$bet_id, $data['user_id']]);
        header("Refresh:0");
        die();
    }
}

if ($lot) {
    $page_content = include_template('lot.php', [
        'current_price' => $current_price,
        'min_bet' => $min_bet,
        'value' => $value,
        'errors' => $errors,
        'categories' => $categories,
        'lot' => $lot,
        'bets_history' => $bets_history
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $lot['name']
]);

print($layout_content);
