Стэк: PHP 5.6-7.4, MySQL, redis
Нельзя использовать фреймворки.

1. Необходимо создать таблицу в MySQL для хранения книги. Книга поступает построчно, тоесть входные данные - строки. Размер строки в среднем 3-5мб. Нужно избежать повторения, каждая строка должна быть уникальной. Напишите структуру БД (TABLE DDL) и код, который обеспечит запись данных.

2. В проекте есть таблица для логирования событий, в которую добавляется около 300 записей каждую минуту. Таблица довольно большая, поэтому каждый месяц данные из неё необходимо архивировать.
Задача перенести данные за последний месяц из рабочей таблицы в архивную и очистить рабочую, при этом не потеряв никакие данные.
Нельзя использовать partitions.
Таблица:
CREATE TABLE `logs` (
`created_at` DATETIME NOT NULL DEFAULT NOW(),
`text` TEXT,
INDEX logs_created_at (`created_at`)
)

3. Имеется БД Redis. На PHP необходимо реализовать две функции: создание и авторизация пользователя с помощью данной базы. Требование: при создании пользователя нужно проверять зарегистрирован ли такой пользователь.
Данные пользователя должны содержать логин, имя, почтовый адрес и т.п.

Установка
1) composer install
2) .env внести настройки для подключения к БД MySQL и загрузке файлов (для LOAD DATE, в my.ini secure-file-priv)
3) подключение к Redis я исползовал по умол.
4) создания БД CREATE DATABASE mydatabase CHARACTER SET utf8 COLLATE utf8_general_ci;
5) запуск миграций vendor/bin/doctrine-migrations migrate

Решение
1) Проверку строк я реализовал через "Бинарно-безопасное сравнение строк", а так же по кличеству символов (этот способ можно использовать если, мы уверены, что количество не совпадет)
    Запись можно отравлять через метод POST на страницу books, тело: name, test в формате json
2) Для 2й задачи, я создал 2 табл. одну на движке InnoDB, а вторую ARCHIVE;
    * логи достаются парционно (стоит по 4 записи, это тестово, на 1000 будет норм работать, можно поиграться) и пишуться в файл
    * запуск команды "php bin\app.php archive:logs:previous_month" (можно повесить на крон), данные читются из файл и пишуться в тал. archive_logs
3) Для регистрация POST users/registration, тело: name, last_name, phone, password, email, из которых обязательные name, phone, password
    Для авторизации все тоже самое только страница users/login, обязательные поля: phone, password
    
Что нужно доделать:
- доделать класс Response, чтоб он взял на себя ответсвтенность за вывод, сейчас же это берет контроллер
- отрефакторить екшены, так как return должен быть один.
- привести к одному стандарту маршрутизацию.
- перенести все на докер окружение