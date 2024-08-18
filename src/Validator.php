<?php

namespace OlegTatarenko\Sitemap;

use DateTime;
use OlegTatarenko\Sitemap\Exceptions\dirNotCreated;
use OlegTatarenko\Sitemap\Exceptions\invalidChangeFreqs;
use OlegTatarenko\Sitemap\Exceptions\invalidDateFormat;
use OlegTatarenko\Sitemap\Exceptions\invalidFileFormat;
use OlegTatarenko\Sitemap\Exceptions\invalidLenURL;
use OlegTatarenko\Sitemap\Exceptions\invalidPriority;

/**
 * Валидация входных данных
 * @class Validator
 * @package OlegTatarenko\Sitemap
 */
class Validator
{
    /** @const array EXT - допустимые типы для создания файлов */
    const EXT = ['xml', 'csv', 'json'];

    /** @const string DATEFORMAT - допустимый формат даты последнего изменения страницы*/
    const DATEFORMAT = 'Y-m-d';

    /** @const int LENURL - максимальная длина URL-адреса страницы, ед. изм. длины - символы */
    const LENURL = 2048;

    /** @const array CHANGEFREQS - допустимые значения периодичности обновления страницы  */
    const CHANGEFREQS = [
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never',
    ];

    /** @const array PRIORITIES - список допустимых значений приоритетности парсинга */
    const PRIORITIES = [
        0.0,
        0.1,
        0.2,
        0.3,
        0.4,
        0.5,
        0.6,
        0.7,
        0.8,
        0.9,
        1.0,
    ];

    /** @param string $fileFormat
     * @throws invalidFileFormat
     */
    public static function isValidFileFormat($fileFormat)
    {
        if (!in_array($fileFormat, self::EXT)){
            throw new invalidFileFormat(
                sprintf('invalidFileFormat Exception: Указан недопустимый формат файла: %s. Допустимы следующие форматы: %s', $fileFormat, implode(', ', self::EXT))
            );

        }
    }

    /** @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws invalidDateFormat
     */
    public static function isValidDateFormat($pages)
    {
        foreach ($pages as $page){
            if (key_exists('lastmod', $page)){
                $d = DateTime::createFromFormat(self::DATEFORMAT, $page['lastmod']);

                if (!($d && $d->format(self::DATEFORMAT) === $page['lastmod'])) {
                    throw new invalidDateFormat(
                        sprintf('invalidDateFormat Exception: Указан недопустимый формат даты последнего изменения страницы: %s. Допустимый формат: %s', $page['lastmod'], self::DATEFORMAT)
                    );
                }
            }
        }
    }

    /** @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws invalidLenURL
     */
    public static function isValidLenURL($pages)
    {
        foreach ($pages as $page) {
            if (mb_strlen($page['loc']) > self::LENURL){
                throw new invalidLenURL(
                    sprintf('invalidLenURL Exception: Длина URL-адреса страницы не должна превышать %s символов.', self::LENURL)
                );
            }
        }
    }

    /** @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws invalidChangeFreqs
     */
    public static function isValidChangeFreqs($pages)
    {

        foreach ($pages as $page) {
            if (key_exists('changefreq', $page)){

                if (!in_array($page['changefreq'], self::CHANGEFREQS)){
                    throw new invalidChangeFreqs(
                        sprintf('invalidChangeFreqs Exception: Указана недопустимая периодичность обновления страницы: %s. Допустимы следующие значения: %s', $page['changefreq'], implode(', ', self::CHANGEFREQS))
                    );
                }
            }
        }
    }

    /** @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws invalidPriority
     */
    public static function isValidPriority($pages)
    {
        foreach ($pages as $page) {
            if (key_exists('priority', $page)){

                if (!in_array($page['priority'], self::PRIORITIES)){
                    throw new invalidPriority(
                        sprintf('invalidPriority Exception: Указана недопустимая приоритетность парсинга страницы: %s. Допустимы следующие значения: %s', $page['priority'], implode('; ', self::PRIORITIES))
                    );
                }
            }
        }
    }

    /** @param string $savePath список страниц сайта в виде массива массивов с параметрами
     * @throws dirNotCreated
     */
    public static function isDir($savePath)
    {
        $dirPath = (pathinfo($savePath)['dirname']);

        if (!is_dir($dirPath)){
            if (!mkdir($dirPath, 0755)){
                throw new dirNotCreated(sprintf('dirNotCreated Exception: Не удалось создать директорию по указанному вами пути: %s.', $dirPath));
            }
        }
    }
}