<?php

/**
 * Округляет введёную стоимость лота до целого числа и возвращает значение со знаком '₽'
 *
 * @param int $price Целое или дробное число
 *
 * @return string Целое число со знаком рубля через пробел
 */
function format_sum($price)
{
    $format_price = ceil($price);
    if ($format_price > 1000) {
        $format_price = number_format($format_price, 0, ',', ' ');
    }
    return $format_price  . ' ' . '₽';
}

/**
 * Показывает разницу во времени в часах и минутах до окончания публикации лота
 *
 * @param string $date Дата в виде строки
 *
 * @return mixed Время в часах и минутах, разделение через ':' либо строка "Торги окончены"
 */
function get_date_range($date)
{
    $seconds_range = strtotime($date) - time();
    $hours = str_pad((floor($seconds_range / 3600)), 2, '0', STR_PAD_LEFT);
    $timer = 'Торги окончены';

    if ($seconds_range >= 0) {
        $timer = $hours . ':' . date("i", $seconds_range);
    }

    return $timer;
}

/**
 * Показывает разницу в часах между введёной датой и нынешней
 *
 * @param string $date Дата в виде строки
 *
 * @return int Разница в часах, округлённая в меньшую сторону
 */
function get_hours($date)
{
    $seconds_range = strtotime($date) - time();

    return floor($seconds_range / 3600);
}

/**
 * Показывает список лотов, отсортированный по дате добавления от новых к старым
 *
 * @param mysqli $link  Ресурс соединения
 *
 * @return array Массив с лотами
 */
function get_lots($link)
{
    if ($link) {
        $sql = 'SELECT lot_id, l.name AS lot_name, c.name AS category_name, starting_price, img, date_end, c.category_id
        FROM lots l
        JOIN categories c ON l.category_id = c.category_id
        ORDER BY date_add DESC';
        $result = mysqli_query($link, $sql);
    } else {
        $result = mysqli_connect_error();
    }

    if ($result) {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $array = mysqli_error($link);
    }

    return $array;
}

/**
 * Показывает список категорий
 *
 * @param mysqli $link  Ресурс соединения
 *
 * @return array Массив категорий
 */
function get_categories($link)
{
    if ($link) {
        $sql = 'SELECT * FROM categories';
        $result = mysqli_query($link, $sql);
    } else {
        $result = mysqli_connect_error();
    }

    if ($result) {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $array = mysqli_error($link);
    }

    return $array;
}

/**
 * Показывает лот по его идентификатору
 *
 * @param mysqli $link  Ресурс соединения
 * @param mixed Данные для заполнения
 *
 * @return array Массив с опубликованным лотом
 */
function get_lot_by_id($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT l.*, c.name AS category_name
    FROM lots l
    JOIN categories c ON l.category_id
    = c.category_id WHERE lot_id = ?';
    $stmt = db_get_prepare_stmt($link, $sql, [$data]);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_array($result);
}

/**
 * Ограничивает количество вводимых символов
 *
 * @param string $value Валидируемая строка
 * @param int $min Минимальное число символов
 * @param int $max Максимальное число символов
 *
 * @return string В случае не соответствия условию выдаёт сообщение об ошибке
 */
function validate_length($value, $min, $max)
{
    if ($value) {
        $length = strlen($value);
        if ($length < $min or $length > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
}

/**
 * Ограничивает ввод отрицательных и нулевых значений стоимости лота
 *
 * @param int $value Валидируемое число
 *
 * @return string В случае не соответствия условию выдаёт сообщение об ошибке
 */
function validate_price($value)
{
    if ($value <= 0) {
        return 'Значение должно быть числом больше 0';
    }
}

/**
 * Ограничивает ввод отрицательных, нулевых и дробных значений шага ставки
 *
 * @param int $value Валидируемое число
 *
 * @return string В случае не соответствия условию выдаёт сообщение об ошибке
 */
function validate_bet_step($value)
{
    if (!is_int($value) || $value <= 0) {
        return 'Значение должно быть целым числом больше 0';
    }
}

/**
 * Сравнивает введённую категорию с категорией из БД
 *
 * @param int $id Идентификатор в списке категорий
 * @param array $category_list Массив с идентификаторами категорий из БД
 *
 * @return string В случае не соответствия условию выдаёт сообщение об ошибке
 */
function validate_category_id($id, $category_list)
{
    if (!in_array($id, $category_list)) {
        return 'Выберите категорию из списка';
    }
}

/**
 * Сравнивает введёную дату с нынешней на наличие разницы в сутки
 *
 * @param string $value Дата в виде строки
 *
 * @return string В случае не соответствия условию выдаёт сообщение об ошибке
 */
function is_actual_date($value)
{
    $seconds_range = strtotime($value) - time();

    if ($seconds_range < 86400) {
        return 'Указаная дата должна быть больше текущей хотя бы на 1 день';
    }
}

/**
 * Добавляет лот в базу данных
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return bool Возвращает true в случае успешного завершения или false в случае возникновения ошибки
 */
function add_lot($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'INSERT INTO lots (name, category_id, description, starting_price, bet_step, date_end, img, user_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return $result;
}

/**
 * Добавляет пользователя в базу данных
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return bool Возвращает true в случае успешного завершения или false в случае возникновения ошибки
 */
function add_user($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = 'INSERT INTO users (email, name, password, contacts)
    VALUES (?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$data['email'], $data['name'], $password, $data['contacts']]);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return $result;
}

/**
 * Сравнивает email пользователя при авторизации с наличием в БД
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return mysqli_result Возвращает false в случае возникновения ошибки
 */
function get_email_comparison($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $email = mysqli_real_escape_string($link, $data['email']);
    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }
    return $result;
}

/**
 * Полнотекстовый поиск по лотам
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return array Массив лотов, отсортированный по дате добавления от новых к старым
 */
function search_by_lots($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT lot_id, l.name AS lot_name, c.name AS category_name, starting_price, img, date_end, c.category_id
    FROM lots l
    JOIN categories c ON l.category_id = c.category_id
    WHERE date_end > NOW() AND MATCH(l.name, description)
    AGAINST(?) ORDER BY date_add DESC LIMIT ? OFFSET ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Сортировка лотов по категориям
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return array Массив лотов, отсортированный категориям
 */
function get_lots_by_category($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT lot_id, l.name AS lot_name, c.name AS category_name, starting_price, img, date_end, c.category_id
    FROM lots l
    JOIN categories c ON l.category_id = c.category_id
    WHERE date_end > NOW() AND c.category_id = ?
    ORDER BY date_add DESC LIMIT ? OFFSET ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Добавляет ставку к лоту
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return bool Возвращает true в случае успешного завершения или false в случае возникновения ошибки
 */
function add_bet($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'INSERT INTO bets (sum, user_id, lot_id) VALUES ( ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return $result;
}

/**
 * Получить список ставок для лота по его идентификатору с сортировкой по дате
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return array Массив лотов, отсортированный по дате добавления от новых к старым
 */
function get_bets_by_lot_id($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT * FROM bets WHERE lot_id = ? ORDER BY date_add DESC';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Добавляет в таблицу с пользователями отпубликованный ими лот
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return bool Возвращает true в случае успешного завершения или false в случае возникновения ошибки
 */
function add_lot_id_to_user($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'UPDATE users SET lot_id = ? WHERE user_id = ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return $result;
}

/**
 * Добавляет в таблицу с пользователями сделанную ими ставку
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return bool Возвращает true в случае успешного завершения или false в случае возникновения ошибки
 */
function add_bet_id_to_user($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'UPDATE users SET bet_id = ? WHERE user_id = ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return $result;
}

/**
 * Получение всех ставок пользователя
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return array Массив ставок
 */
function get_bets_by_user_id($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT b.date_add, b.sum, b.user_id, l.date_end, l.user_win_id, c.name
    AS category_name, l.name, l.lot_id, l.img, u.contacts
    FROM bets b
    JOIN lots l ON b.lot_id = l.lot_id
    JOIN categories c ON l.category_id = c.category_id
    JOIN users u ON l.user_id = u.user_id
    WHERE b.user_id = ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение истории ставок для лота
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return array Массив ставок
 */
function get_bets_history_by_lot_id($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT u.name, b.sum, b.date_add
    FROM bets b
    JOIN users u ON b.user_id = u.user_id
    WHERE b.lot_id = ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



/**
 * Вычисляем победителя по последней ставке
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return array Массив с данными о победителе
 */
function get_winner($link)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'SELECT l.lot_id, l.name AS lot_name, l.date_end, l.user_win_id,
    b.date_add, b.sum, b.user_id, u.name, u.email
    FROM lots l
    JOIN bets b ON l.lot_id = b.lot_id
    JOIN users u ON b.user_id = u.user_id
    WHERE date_end <= NOW()
    AND user_win_id IS NULL
    AND b.sum = (SELECT MAX(b.sum) FROM bets b WHERE b.lot_id = l.lot_id)';
    $stmt = db_get_prepare_stmt($link, $sql);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



/**
 * Добавляет в таблицу с лотом победителя
 *
 * @param mysqli $link  Ресурс соединения
 * @param array $data Данные для заполнения
 *
 * @return bool Возвращает true в случае успешного завершения или false в случае возникновения ошибки
 */
function add_winner_to_lot($link, $data)
{
    if (!$link) {
        $result = mysqli_connect_error();
    }
    $sql = 'UPDATE lots SET user_win_id = ? WHERE lot_id = ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if ($stmt) {
        $result = mysqli_stmt_execute($stmt);
    } else {
        $result = mysqli_error($link);
    }

    return $result;
}
