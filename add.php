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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filename = $_FILES['image']['name'];
    $_POST['img'] = 'uploads/' . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);

    print_r($_POST);

    $sql = 'INSERT INTO lots (date_add, name, category_id, description, img, starting_price, bet_step, date_end, user_id)
    VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, 1)';

    $stmt = db_get_prepare_stmt($connect, $sql, $_POST);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $lot_id = mysqli_insert_id($connect);

        header("Location: lot.php?id=" . $lot_id);
    } else {
        print mysqli_error($connect);
    };
};

print($layout_content);
