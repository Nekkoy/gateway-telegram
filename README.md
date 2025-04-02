# gateway-telegram
SMS Gateway для сервиса [telegram.org](https://telegram.org)

Установка:
```
composer require nekkoy/gateway-telegram
```

Конфигурация `.env`
===============
```
# Включить/выключить модуль
TELEGRAM_ENABLED=true

# Токен бота
TELEGRAM_TOKEN=

# Адрес базы данных
TELEGRAM_DB_HOST=localhost

# Логин подключения к базе
TELEGRAM_DB_LOGIN=root

# Пароль подключения к базе
TELEGRAM_DB_PASSWORD=

# Название базы данных с контактами
TELEGRAM_DB_NAME=bot

# SQL запрос для поиска контакта
# в :entity будет подставлен номер телефона с % в начале для поиска по LIKE
# в :uid будет подставлен ID пользователя
# обязательно в результате должна быть только одна запись, по этому используется LIMIT 1
TELEGRAM_DB_QUERY="SELECT `telegram_id` FROM `users` WHERE `phone_number` LIKE :entity OR `billing_uid`=:uid"

# Название поля с уникальным ID пользователя telegram
TELEGRAM_DB_USERID_FIELD=telegram_id
```

Использование
===============

Создайте DTO сообщения, передав первым параметром текст, а вторым — номер получателя (третьим можно передать ID пользователя):
```
$message = new \Nekkoy\GatewayAbstract\DTO\MessageDTO("test", "+380123456789", 81265672);
```

Затем вызовите метод отправки сообщения через фасад:
```
/** @var \Nekkoy\GatewayAbstract\DTO\ResponseDTO $response */
$response = \Nekkoy\GatewayTelegram\Facades\GatewayTelegram::send($message);
```

Метод возвращает DTO-ответ с параметрами:
 - message - сообщение об успешной отправке или ошибке
 - code - код ответа:
   - code < 0 - ошибка модуля
   - code > 0 - ошибка HTTP
   - code = 0 - успех
 - id - ID сообщения
