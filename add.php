<?php

require_once('functions.php');
require_once('data.php');
require_once('config/db.php');

$categories = get_categories($connect);
$cats_ids = array_column($categories, 'category_id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required_fields = ['name', 'category_id', 'description', 'image', 'starting_price', 'bet_step', 'date_end'];
    $errors = [];

    $rules = [
        'name' => function($value) {
            return validateLength($value, 1, 50);
        },
        'category_id' => function($value) use ($cats_ids) {
            return validateCategoryId($value, $cats_ids);
        },
        'description' => function($value) {
            return validateLength($value, 1, 100);
        },
        'starting_price' => function($value) {
            return validatePrice($value);
        },
        'bet_step' => function($value) {
            return validateBetStep($value);
        },
        'date_end' => function($value) {
            return validateDate($value);
        }
    ];

    $data = filter_input_array(INPUT_POST, [
        'name' => FILTER_SANITIZE_STRING,
        'category_id' => FILTER_VALIDATE_INT,
        'description' => FILTER_SANITIZE_STRING,
        'starting_price' => FILTER_VALIDATE_INT,
        'bet_step' => FILTER_VALIDATE_INT,
        'date_end' => FILTER_SANITIZE_STRING,
    ]);

    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $validationResult = $rule($value);
            if ($validationResult) {
                $errors[$key] = $validationResult;
            };
        };

        if(in_array($key, $required_fields) && empty($value)) {
            $errors[$key] = "Заполните это поле";
        };
    };

    $errors = array_filter($errors);

    if (!empty($_FILES['image']['name'])) {
        $filename = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type =  finfo_file($file_info, $tmp_name);
        if ($file_type !== "image/png" and $file_type !== "image/jpeg") {
            $errors['image'] = "Загрузите картинку в формате jpg/jpeg/png";
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $data['img'] = 'uploads/' . $filename;
        };
    } else {
        $errors['image'] = "Вы не загрузили файл";
    };

    if (count($errors)) {
        $page_content = include_template('add-lot.php', ['errors' => $errors, 'categories' => $categories]);
    } else {
        $sql = 'INSERT INTO lots (name, category_id, description, starting_price, bet_step, date_end, img, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)';
        $stmt = db_get_prepare_stmt($connect, $sql, $data);
        if ($stmt) {
            $result = mysqli_stmt_execute($stmt);
        } else {
            print mysqli_stmt_error($stmt);
        };

        if ($result) {
            $lot_id = mysqli_insert_id($connect);

            header("Location: lot.php?id=" . $lot_id);
        } else {
            print mysqli_error($connect);
        };
    };
} else {
    $page_content = include_template('add-lot.php', ['categories' => $categories]);
};

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
