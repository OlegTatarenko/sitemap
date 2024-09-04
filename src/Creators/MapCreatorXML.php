<?php

namespace OlegTatarenko\Sitemap\Creators;

use OlegTatarenko\Sitemap\MapsCreator;

/**
 * Создает отформатированную в xml формате строку с картой сайта
 * @class MapCreatorXML
 * @package OlegTatarenko\Sitemap
 */
class MapCreatorXML implements MapsCreator
{
    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return string отформатированная в xml формате строка с картой сайта
     */
    public function createMap(array $pages):string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<urlset xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance"' . "\n" .
            'xsi:schemaLocation="https://www.sitemaps.org/schemas/sitemap/0.9https:///www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"' . "\n" .
            'xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($pages as $page) {
            $xml .= '<url>' . "\n" .
                '<loc>' . htmlspecialchars($page['loc']) . '</loc>' . "\n";

            if (key_exists('lastmod', $page)) {
                $xml .= '<lastmod>' . $page['lastmod'] . '</lastmod>' . "\n";
            }
            if (key_exists('priority', $page)) {
                $xml .= '<priority>' . $page['priority'] . '</priority>' . "\n";
            }
            if (key_exists('changefreq', $page)) {
                $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            }
            $xml .= '</url>' . "\n";
        }
        return $xml . '</urlset>';
    }
}