Фикстуры
===

Команда позволяет экпортировать и импортировать данные:

* Создать необходимые схемы
* из БД в дапм (экпортировать)
* из дампа в БД (импортировать)

Создать необходимые схемы (для Postgres):

```
cd vendor/yii2bundle/yii2-db/bin
php bin schema/create
```

Для экпорта/импорта таблиц по выбору:

```
cd vendor/yii2bundle/yii2-db/bin
php bin fixture
```

Для экпорта/импорта таблицы по ее имени:

```
cd vendor/yii2bundle/yii2-db/bin
php bin fixture/one
```

Для автовыбора можно использовать параметры:

* `-i` - импорт
* `-e` - экспорт
* `-a` - выбрать все
