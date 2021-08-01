<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

if (isset($_SESSION['user'])) {
    header('Location: /index.php');
    die();
}

$categories = get_categories($connect);
$data = filter_input_array(INPUT_POST, [
    'email' => FILTER_SANITIZE_EMAIL,
    'password' => FILTER_SANITIZE_STRING,
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password'];
    $errors = [];

    foreach ($required as $field) {
        if (empty($data[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }

    $user = get_email_comparison($connect, $data) ?
    mysqli_fetch_array(get_email_comparison($connect, $data), MYSQLI_ASSOC) : null;
    $errors = array_filter($errors);

    if (!count($errors) && $user) {
        if (password_verify($data['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }

    if ($data['email'] && !$user) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('login.php', ['categories' => $categories, 'data' => $data,
        'errors' => $errors]);
    } else {
        header('Location: /index.php');
        die();
    }
} else {
    $page_content = include_template('login.php', ['categories' => $categories, 'data' => $data]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Вход'
]);

print($layout_content);
