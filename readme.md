# Тестовое задание

### Установка
Для установки зависимостей необходимо выполнить команду ``composer install``
### Конфигурация хранилищ
Для конфигурации хранилищ служит файл ``config.json``

### Выполнение команд:
Для выполения команд служит файл ``command``

``php command DATASOURCE ACTION KEY [VALUE]``

DATASOURCE - хранилище данных, например, redis или memcached.
Для добавления нового класса хранилища необходимо создать класс ``NewDataSourceDS`` (DS является обязательной частью имени класса) в папке ``Services/Datasources`` и имплементировать ему интерфейс ``DataSourceInterface``

ACTION - желаемое действие. Доступны действия ``set``, ``delete``, ``get``

KEY - имя ключа

VALUE - значение для добавления, используется только с действие ``set``

### Тестовая страница
Страница для просмотра результатов находится по ссылке index.html