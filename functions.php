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
    if ($seconds_range >= 0) {
        return date("H:i", $seconds_range);
    } else {
        return print('Время истекло');
    };
};

function get_hours($date) {
    $date_end = strtotime($date);
    $secs_to_end = $date_end - time();

    return floor($secs_to_end / 3600);
};
