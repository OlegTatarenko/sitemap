<?php

namespace OlegTatarenko\Sitemap;

/**
 * Создание карты сайта (строки), отформатированной или в xml, или в csv, или в json
 * @class MapCreator
 * @package OlegTatarenko\Sitemap
 */
class MapCreator
{
    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return string карта сайта в формате xml
     * */
    public static function createXML(array $pages): string
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

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return string карта сайта в формате csv
     * */
    public static function createCSV(array $pages): string
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

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @return false|string карта сайта в формате json
     * */
    public static function createJSON(array $pages): false|string
    {
        return json_encode($pages);
    }
}