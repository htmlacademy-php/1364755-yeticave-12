<?php
function format_sum($price) {
    $format_price = ceil($price);
    if ($format_price > 1000) {
        $format_price = number_format($format_price, 0, ',', ' ');
    };
    return $format_price  . ' ' . '₽';
};

function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
    return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
};

function get_date_range($date) {
    $seconds_range = strtotime($date) - time();
    $hours = str_pad((floor($seconds_range / 3600)), 2, '0', STR_PAD_LEFT);
    $timer = 'Время истекло';

    if ($seconds_range >= 0) {
        $timer = $hours . ':' . date("i", $seconds_range);
    };

    return $timer;
};

function get_hours($date) {
    $seconds_range = strtotime($date) - time();

    return floor($seconds_range / 3600);
};

function get_lots($connect) {
    if ($connect) {
        $new_lots = 'SELECT lot_id, l.name AS lot_name, c.name AS category_name, starting_price, img, date_end, c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC';
        $result = mysqli_query($connect, $new_lots);
    } else {
        $result = mysqli_connect_error();
    };

   if ($result) {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $array = mysqli_error($connect);
    };

    return $array;
};

function get_categories($connect) {
    if ($connect) {
        $all_categories = 'SELECT * FROM categories';
        $result = mysqli_query($connect, $all_categories);
    } else {
        $result = mysqli_connect_error();
    };

   if ($result) {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $array = mysqli_error($connect);
    };

    return $array;
};

function get_lot_by_id($connect) {
    if ($connect) {
        $id = filter_input(INPUT_GET, 'id');
    	$lot = 'SELECT l.*, c.name AS category_name FROM lots l JOIN categories c ON l.category_id = c.category_id WHERE lot_id =' . $id;
        $result = mysqli_query($connect, $lot);
    } else {
        $result = mysqli_connect_error();
    };

   if ($result) {
        $array = mysqli_fetch_assoc($result);
    } else {
        $array = mysqli_error($connect);
    };

    return $array;
};

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}
