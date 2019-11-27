<?php

require_once './vendor/autoload.php';

use models\Mysql;
use modules\wallet\models\services\RateService;

Mysql::connect();

error_reporting(E_ALL);

echo "<h2>Соединение TCP/IP</h2>\n";

/* Создаём сокет TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

$result = socket_connect($socket, '127.0.0.1',889);
if ($result === false) {
    echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

echo "Читаем ответ:\n\n";

while ($rates = socket_read($socket, 2048)) {
    list($usd, $rub) = explode('_',$rates);

    echo 'USD: '.$usd.PHP_EOL.'RUB: '.$rub.PHP_EOL;

    $rateService = new RateService();

    $rateService->addRates($usd, $rub);

}

echo "Закрываем сокет...";
//socket_close($socket);
echo "OK.\n\n";
