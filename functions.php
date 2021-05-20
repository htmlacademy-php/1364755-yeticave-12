<?php
function format_sum($price) {
    $format_price = ceil($price);
    if ($format_price > 1000) {
        $format_price = number_format($format_price, 0, ',', ' ');
    };
    $final_price = $format_price  . ' ' . '₽';

    return $final_price;
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

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

function get_date_range($date) {
    $date_end = strtotime($date);
    $secs_to_end = $date_end - time();

    $hours = str_pad((floor($secs_to_end / 3600)), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad((floor(($secs_to_end % 3600) / 60)), 2, '0', STR_PAD_LEFT);

    return $time_to_end = $hours . ':' . $minutes;
};

function get_hours($date) {
    $date_end = strtotime($date);
    $secs_to_end = $date_end - time();

    return $hours = floor($secs_to_end / 3600);
};
