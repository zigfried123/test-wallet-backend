1. git clone https://github.com/zigfried123/test-wallet-backend
2. composer install
3. поменять настройки config/mysql.php
4. в корне проекта php index.php migrate/up
5. запустить WebSocket сервер php scripts/changeBalanceServer.php
6. запустить WebSocket клиент php scripts/changeBalanceClient.php для записи курсов в бд
