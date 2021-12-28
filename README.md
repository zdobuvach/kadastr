# kadastr

yii2 geopoints kml maps layers

## task

Есть:
- список кадастровых номеров
- геозона

Нужно:
1. Отрисовать на карте контур геозоны
1. Используя api, найти центр кадастровых участков
2. Найти кадастровые участки которые попадают в заданую геозону
3. Вывести список кадастровых участков которые попадают в геозону и список учасков которые не попадают в геозону
4. По клику на кадастровый номер сфокусировать на него карту и вывести маркер геотега в центре участка.

Структура страницы:
- слева 1/4 - панель со списками кадастровых номеров
- справа карта (Leaflet | © OpenStreetMap)
- на карте иконка слои, в ней можно включить / выключить отображение кадастровой карты (слой из https://map.land.gov.ua/)


## solution

### clone and install project

git clone https://github.com/zdobuvach/kadastr

sudo chown -R www-data:www-data kadastr

cd kadastr/

### run servers

docker-compose up -d  --build

### entrance to console

docker-compose exec php sh

### run composer install

composer install

### Now everything works with "Boxes"

But you have the ability to start the migration

#### To do this, you need to configure database connections

config/db.php

#### and the location of test files with geodata etc

config/cadaster.php

#### and finally begin migration

yii migrate/down 1

yii migrate

### run webpage

http://localhost:8000

### run webpage kadastr all points

http://localhost:8000/point/

### run webpage kadastr inside points

http://localhost:8000/point/inside

### run webpage kadastr outside points

http://localhost:8000/point/outside

## done

### config

config/cadaster.php

### db tables

test_quartsoft.cadastral_numbers

### Yii

#### Migration

migrations/m211226_075529_create_cadastral_numbers_table

#### controllers

controllers/PointController.php <br>  

#### models

models/Cadaster.php <br>
models/CadastralNumbers.php <br>
