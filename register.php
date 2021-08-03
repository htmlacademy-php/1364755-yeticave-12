<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$categories = get_categories($connect);
$data = filter_input_array(INPUT_POST, [
    'email' => FILTER_SANITIZE_EMAIL,
    'name' => FILTER_SANITIZE_STRING,
    'password' => FILTER_SANITIZE_STRING,
    'contacts' => FILTER_SANITIZE_STRING,
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = ['email', 'password', 'name', 'contacts'];
    $errors = [];

    $rules = [
        'email' => function ($value) {
            return validate_length($value, 1, 50);
        },
        'name' => function ($value) {
            return validate_length($value, 1, 30);
        },
        'password' => function ($value) {
            return validate_length($value, 5, 20);
        },
        'contacts' => function ($value) {
            return validate_length($value, 1, 100);
        }
    ];

    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $validationResult = $rule($value);
            if ($validationResult) {
                $errors[$key] = $validationResult;
            }
        }

        if (in_array($key, $required_fields) && empty($value)) {
            $errors[$key] = 'Заполните это поле';
        }
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) && !empty($data['email'])) {
        $errors['email'] = 'Введите коректный email';
    }

    if (mysqli_fetch_array(get_email_comparison($connect, $data), MYSQLI_ASSOC)) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template('register.php', ['errors' => $errors, 'categories' => $categories,
        'data' => $data]);
    } else {
        add_user($connect, $data);
        header("Location: /login.php");
        die();
    }
} else {
    $page_content = include_template('register.php', ['categories' => $categories, 'data' => $data]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Регистрация'
]);

print($layout_content);
