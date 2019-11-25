1. composer install
2. поменять настройки config/mysql.php
2. в корне проекта php index.php migrate/up
3. запустить WebSocket сервер php scripts/changeBalanceServer.php
4. запустить WebSocket клиент php scripts/changeBalanceClient.php для записи курсов в бд
