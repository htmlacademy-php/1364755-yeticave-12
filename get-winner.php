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

        $domen = $_SERVER['HTTP_HOST'];
        $smtp_host = 'smtp.mailtrap.io';
        $email_from = 'keks@phpdemo.ru';
        $username = '75f3c8c888f4c0';
        $password = 'd3bf00f9a2376d';
        $smtp_port = '2525';
        $target_email = $winner['email'];
        $target_name = $winner['name'];
        $message_content = include_template('email.php', ['winner' => $winner, 'domen' => $domen]);

        $transport = (new Swift_SmtpTransport($smtp_host, $smtp_port))
            ->setUsername($username)
            ->setPassword($password)
            ->setEncryption('TLS');

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Ваша ставка победила'))
            ->setFrom([$email_from => 'Кекс'])
            ->setTo([$target_email => $target_name])
            ->setBody($message_content, 'text/html');

        $result = $mailer->send($message);

        if ($result) {
            print('Письмо успешно отправлено');
        } else {
            print('Не удалось отправить письмо');
        }
    }
}
