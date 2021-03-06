<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    die();
}

$categories = get_categories($connect);
$cats_ids = array_column($categories, 'category_id');
$data = filter_input_array(INPUT_POST, [
    'name' => FILTER_SANITIZE_STRING,
    'category_id' => FILTER_VALIDATE_INT,
    'description' => FILTER_SANITIZE_STRING,
    'starting_price' => FILTER_VALIDATE_INT,
    'bet_step' => FILTER_VALIDATE_INT,
    'date_end' => FILTER_SANITIZE_STRING,
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = ['name', 'category_id', 'description', 'image', 'starting_price', 'bet_step', 'date_end'];
    $errors = [];

    $rules = [
        'name' => function ($value) {
            return validate_length($value, 1, 50);
        },
        'category_id' => function ($value) use ($cats_ids) {
            return validate_category_id($value, $cats_ids);
        },
        'description' => function ($value) {
            return validate_length($value, 1, 100);
        },
        'starting_price' => function ($value) {
            return validate_price($value);
        },
        'bet_step' => function ($value) {
            return validate_bet_step($value);
        },
        'date_end' => function ($value) {
            return is_actual_date($value);
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

    $errors = array_filter($errors);

    if (!empty($_FILES['image']['name'])) {
        $filename = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type =  finfo_file($file_info, $tmp_name);
        if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
            $errors['image'] = 'Загрузите картинку в формате jpg/jpeg/png';
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $data['img'] = 'uploads/' . $filename;
        }
    } else {
        $errors['image'] = 'Вы не загрузили файл';
    }

    if (!is_date_valid($data['date_end']) && !empty($data['date_end'])) {
        $errors['date_end'] = 'Дата должна быть в формате ГГГГ-ММ-ДД';
    }

    $data['user_id'] = $_SESSION['user']['user_id'];

    if (empty($errors)) {
        add_lot($connect, $data);
        $lot_id = mysqli_insert_id($connect);
        add_lot_id_to_user($connect, [$lot_id, $data['user_id']]);
        header("Location: lot.php?id=" . $lot_id);
        die();
    }

    $page_content = include_template('add-lot.php', ['errors' => $errors, 'categories' => $categories,
    'data' => $data]);
} else {
    $page_content = include_template('add-lot.php', ['categories' => $categories, 'data' => $data]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота'
]);

print($layout_content);
