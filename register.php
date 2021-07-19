<?php
session_start();

require_once('functions.php');
require_once('data.php');
require_once('config/db.php');

$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required_fields = ['email', 'password', 'name', 'contacts'];
    $errors = [];

    $rules = [
        'email' => function($value) {
            return validate_length($value, 1, 50);
        },
        'name' => function($value) {
            return validate_length($value, 1, 30);
        },
        'password' => function($value) {
            return validate_length($value, 5, 20);
        },
        'contacts' => function($value) {
            return validate_length($value, 1, 100);
        }
    ];

    $data = filter_input_array(INPUT_POST, [
        'email' => FILTER_VALIDATE_EMAIL,
        'name' => FILTER_SANITIZE_STRING,
        'password' => FILTER_SANITIZE_STRING,
        'contacts' => FILTER_SANITIZE_STRING,
    ]);

    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $validationResult = $rule($value);
            if ($validationResult) {
                $errors[$key] = $validationResult;
            }
        }

        if(empty($value)) {
            $errors[$key] = "Заполните это поле";
        }
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Введите коректный email";
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($connect, $data['email']);
        $sql = "SELECT user_id FROM users WHERE email = '$email'";
        $result = mysqli_query($connect, $sql);

        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template('register.php', ['errors' => $errors, 'categories' => $categories]);
    } else {
        add_user($connect, $data);
    }

} else {
    $page_content = include_template('register.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Регистрация',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
