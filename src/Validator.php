<?php

namespace OlegTatarenko\Sitemap;

use DateTime;
use OlegTatarenko\Sitemap\Exceptions\DirNotCreated;
use OlegTatarenko\Sitemap\Exceptions\EmptyPages;
use OlegTatarenko\Sitemap\Exceptions\InvalidChangeFreqs;
use OlegTatarenko\Sitemap\Exceptions\InvalidDateFormat;
use OlegTatarenko\Sitemap\Exceptions\InvalidFileFormat;
use OlegTatarenko\Sitemap\Exceptions\InvalidLenURL;
use OlegTatarenko\Sitemap\Exceptions\InvalidPriority;
use OlegTatarenko\Sitemap\Exceptions\NotExistLoc;

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

    /**
     * @param string $fileFormat
     * @throws InvalidFileFormat
     */
    public static function isValidFileFormat(string $fileFormat): void
    {
        if (!in_array($fileFormat, self::EXT)) {
            throw new InvalidFileFormat(
                sprintf('InvalidFileFormat Exception: Указан недопустимый формат файла: %s. Допустимы следующие форматы: %s', $fileFormat, implode(', ', self::EXT))
            );

        }
    }

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws InvalidDateFormat
     */
    public static function isValidDateFormat(array $pages): void
    {
        foreach ($pages as $page){
            if (key_exists('lastmod', $page)) {
                $d = DateTime::createFromFormat(self::DATEFORMAT, $page['lastmod']);

                if (!($d && $d->format(self::DATEFORMAT) === $page['lastmod'])) {
                    throw new InvalidDateFormat(
                        sprintf('InvalidDateFormat Exception: Указан недопустимый формат даты последнего изменения страницы: %s. Допустимый формат: %s', $page['lastmod'], self::DATEFORMAT)
                    );
                }
            }
        }
    }

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws InvalidLenURL
     */
    public static function isValidLenURL(array $pages): void
    {
        foreach ($pages as $page) {
            if (mb_strlen($page['loc']) > self::LENURL) {
                throw new InvalidLenURL(
                    sprintf('InvalidLenURL Exception: Длина URL-адреса страницы не должна превышать %s символов.', self::LENURL)
                );
            }
        }
    }

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws InvalidChangeFreqs
     */
    public static function isValidChangeFreqs(array $pages): void
    {

        foreach ($pages as $page) {
            if (key_exists('changefreq', $page)) {

                if (!in_array($page['changefreq'], self::CHANGEFREQS)) {
                    throw new InvalidChangeFreqs(
                        sprintf('InvalidChangeFreqs Exception: Указана недопустимая периодичность обновления страницы: %s. Допустимы следующие значения: %s', $page['changefreq'], implode(', ', self::CHANGEFREQS))
                    );
                }
            }
        }
    }

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws InvalidPriority
     */
    public static function isValidPriority(array $pages): void
    {
        foreach ($pages as $page) {
            if (key_exists('priority', $page)) {

                if (!in_array($page['priority'], self::PRIORITIES)) {
                    throw new InvalidPriority(
                        sprintf('InvalidPriority Exception: Указана недопустимая приоритетность парсинга страницы: %s. Допустимы следующие значения: %s', $page['priority'], implode('; ', self::PRIORITIES))
                    );
                }
            }
        }
    }

    /**
     * @param string $savePath список страниц сайта в виде массива массивов с параметрами
     * @throws DirNotCreated
     */
    public static function isDir(string $savePath): void
    {
        $dirPath = (pathinfo($savePath)['dirname']);

        if (!is_dir($dirPath)) {
            if (!mkdir($dirPath, 0755)) {
                throw new DirNotCreated(sprintf('DirNotCreated Exception: Не удалось создать директорию по указанному вами пути: %s.', $dirPath));
            }
        }
    }

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws EmptyPages
     */
    public static function isEmptyPages(array $pages): void
    {
        if (empty($pages) or !is_array($pages)) {
            throw new EmptyPages('EmptyPages Exception: В полученном массиве страниц нет данных либо полученные данные не являются массивом');
        } else {
            foreach ($pages as $page) {
                if (empty($page)) {
                    throw new EmptyPages('EmptyPages Exception: В полученном массиве одна из страниц не содержит данных');
                }
            }
        }
    }

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @throws NotExistLoc
     */
    public static function isExistLoc(array $pages): void
    {
        foreach ($pages as $page) {
            if (!key_exists('loc', $page)) {
                throw new NotExistLoc('NotExistLoc Exception: В полученных данных отсутствует обязательный тег loc в одной или всех страницах');
            }
        }
    }
}