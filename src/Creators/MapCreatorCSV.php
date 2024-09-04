<?php

namespace OlegTatarenko\Sitemap\Creators;

use OlegTatarenko\Sitemap\MapsCreator;

/**
 * Создает отформатированную в csv формате строку с картой сайта
 * @class MapCreatorCSV
 * @package OlegTatarenko\Sitemap
 */
class MapCreatorCSV implements MapsCreator
{
    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return string отформатированная в csv формате строка с картой сайта
     */
    public function createMap(array $pages): string
    {
        $csv = 'loc;lastmod;priority;changefreq' . "\n";

        foreach ($pages as $page) {
            $csv .= $page['loc'] . ';';
            if (key_exists('lastmod', $page)) {
                $csv .= $page['lastmod'] . ';';
            } else {
                $csv .= ';';
            }
            if (key_exists('priority', $page)) {
                $csv .= $page['priority'] . ';';
            } else {
                $csv .= ';';
            }
            if (key_exists('changefreq', $page)) {
                $csv .= $page['changefreq'] . "\n";
            } else {
                $csv .= "\n";
            }
        }
        return $csv;
    }
}