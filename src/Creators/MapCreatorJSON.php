<?php

namespace OlegTatarenko\Sitemap\Creators;

use OlegTatarenko\Sitemap\MapsCreator;

/**
 * Создает отформатированную в json формате строку с картой сайта
 * @class MapCreatorJSON
 * @package OlegTatarenko\Sitemap
 */
class MapCreatorJSON implements MapsCreator
{
    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return string отформатированная в json формате строка с картой сайта
     * */
    public function createMap(array $pages): string
    {
        return json_encode($pages);
    }
}