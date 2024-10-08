### Библиотека генерации карты сайта
Библиотека позволяет генерировать карту сайта в различных файловых форматах: xml, csv, json


### Установка
```php
composer require olegtatarenko/sitemap
```


### Пример использования
```php
<?php

require 'vendor/autoload.php';

use OlegTatarenko\Sitemap\FileCreater;

$pages = [
    [
        'loc' => 'https://site.ru/',
        'lastmod' => '2024-08-17',
        'priority' => 1,
        'changefreq' => 'hourly'
    ],
    [
        'loc' => 'https://site.ru/news',
        'lastmod' => '2024-08-17',
        'priority' => 0.5,
        'changefreq' => 'weekly'
    ]
];

$file = new FileCreater($pages, 'xml', './upload/sitemap.xml');

try {
    $file->createFileWithSiteMap();
} catch (Exception $e){
    echo $e->getMessage();
}
```
