<?php

error_reporting(E_ALL);

/* Позволяет скрипту ожидать соединения бесконечно. */
set_time_limit(0);


$address = '127.0.0.1';
$port = 889;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
    echo "Не удалось выполнить socket_bind(): причина: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
    echo "Не удалось выполнить socket_listen(): причина: " . socket_strerror(socket_last_error($sock)) . "\n";
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "Не удалось выполнить socket_accept(): причина: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }

    while (1) {
        $usd = mt_rand(60, 70);
        $rub = round(1/$usd,3);
        if (socket_write($msgsock, $usd.'_'.$rub, strlen($usd.'_'.$rub)) === false) {
            break;
        }
        sleep(1);
    }

    socket_close($msgsock);
} while (true);

socket_close($sock);
