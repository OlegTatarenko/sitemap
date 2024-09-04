<?php

namespace OlegTatarenko\Sitemap;

/**
 * Интерфейс MapsCreator определяет метод для создания карты сайты.
 * @interface MapsCreator
 * @package OlegTatarenko\Sitemap
 */
interface MapsCreator
{
    /**
     * Создает отформатированную строку с картой сайта
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return string отформатированная строка с картой сайта
     */
    public function createMap(array $pages):string;
}