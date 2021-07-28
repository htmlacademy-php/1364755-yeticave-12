<?php
function format_sum($price)
{
    $format_price = ceil($price);
    if ($format_price > 1000) {
        $format_price = number_format($format_price, 0, ',', ' ');
    }
    return $format_price  . ' ' . '₽';
}

function get_date_range($date)
{
    $seconds_range = strtotime($date) - time();
    $hours = str_pad((floor($seconds_range / 3600)), 2, '0', STR_PAD_LEFT);
    $timer = 'Время истекло';

    if ($seconds_range >= 0) {
        $timer = $hours . ':' . date("i", $seconds_range);
    }

    return $timer;
}

function get_hours($date)
{
    $seconds_range = strtotime($date) - time();

    return floor($seconds_range / 3600);
}

function get_lots($connect)
{
    if ($connect) {
        $new_lots = 'SELECT lot_id, l.name AS lot_name, c.name AS category_name, starting_price, img, date_end,
        c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC';
        $result = mysqli_query($connect, $new_lots);
    } else {
        $result = mysqli_connect_error();
    }

    if ($result) {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $array = mysqli_error($connect);
    }

    return $array;
}

function get_categories($connect)
{
    if ($connect) {
        $all_categories = 'SELECT * FROM categories';
        $result = mysqli_query($connect, $all_categories);
    } else {
        $result = mysqli_connect_error();
    }

    if ($result) {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $array = mysqli_error($connect);
    }

    return $array;
}

function get_lot_by_id($connect)
{
    if ($connect) {
        $id = filter_input(INPUT_GET, 'id');
        $lot = 'SELECT l.*, c.name AS category_name FROM lots l JOIN categories c ON l.category_id
        = c.category_id WHERE lot_id =' . $id;
        $result = mysqli_query($connect, $lot);
    } else {
        $result = mysqli_connect_error();
    }

    if ($result) {
        $array = mysqli_fetch_assoc($result);
    } else {
        $array = mysqli_error($connect);
    }

    return $array;
}

function validate_length($value, $min, $max)
{
    if ($value) {
        $length = strlen($value);
        if ($length < $min or $length > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
}

function validate_price($value)
{
    if ($value <= 0) {
        return "Значение должно быть числом больше 0";
    }
}

function validate_bet_step($value)
{
    if (!is_int($value) || $value <= 0) {
        return "Значение должно быть целым числом больше 0";
    }
}

function validate_category_id($id, $category_list)
{
    if (!in_array($id, $category_list)) {
        return "Выберите категорию из списка";
    }
}

function is_actual_date($value)
{
    $seconds_range = strtotime($value) - time();

    if ($seconds_range < 86400) {
        return "Указаная дата должна быть больше текущей хотя бы на 1 день";
    }
}

function add_lot($connect, $data)
{
    if (!$connect) {
        $result = mysqli_connect_error();
    }
    $sql = 'INSERT INTO lots (name, category_id, description, starting_price, bet_step, date_end, img, user_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($connect);
    }

    return $result;
}

function add_user($connect, $data)
{
    if (!$connect) {
        $result = mysqli_connect_error();
    }
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = 'INSERT INTO users (email, name, password, contacts)
    VALUES (?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($connect, $sql, [$data['email'], $data['name'], $password, $data['contacts']]);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($connect);
    }

    return $result;
}

function get_email_comparison($connect, $data)
{
    $email = mysqli_real_escape_string($connect, $data['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    return $result = mysqli_query($connect, $sql);
}
