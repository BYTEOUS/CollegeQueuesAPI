# Подключение к БД
Для подключения к БД нужно ввести ее хост, юзернейм,
пороль и прочее в файле `lib/Database.php`,
в переменной `$db_config`.

# Миграция БД
Чтобы создать требуемые таблицы в БД, нужно выполнить миграцию следующей командой:
```
php -f migrate.php
```