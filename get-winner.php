<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');

$is_winner = get_winner($connect);

if ($is_winner) {
    foreach ($is_winner as $winner) {
        $data = [$winner['user_id'], $winner['lot_id']];
        add_winner_to_lot($connect, $data);
    }
}
