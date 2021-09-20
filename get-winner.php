<?php

require_once('functions.php');
require_once('helpers.php');
require_once('config/db.php');
require_once('vendor/autoload.php');

$is_winner = get_winner($connect);

if ($is_winner) {
    foreach ($is_winner as $winner) {
        $data = [$winner['user_id'], $winner['lot_id']];
        add_winner_to_lot($connect, $data);

        $smtp_host = 'mailtrap.io';
        $email_from = 'keks@phpdemo.ru';
        $password = 'htmlacademy';
        $smtp_port = '25';
        $target_email = $winner['email'];
        $target_name = $winner['name'];
        $message_content = include_template('email.php', ['winner' => $winner]);

        $transport = (new Swift_SmtpTransport($smtp_host, $smtp_port))
            ->setUsername($email_from)
            ->setPassword($password);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Ваша ставка победила'))
            ->setFrom([$email_from => 'keks@phpdemo.ru'])
            ->setTo([$target_email => $target_name])
            ->setBody($message_content, 'text/html');

        $result = $mailer->send($message);

        if ($result) {
            print('Сообщение успешно отправлено');
        } else {
            print('Не удалось отправить сообщение');
        }
    }
}
