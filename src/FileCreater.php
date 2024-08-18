<?php

namespace OlegTatarenko\Sitemap;

use Exception;

/**
 * Создание файла с картой сайта в требуемом формате - xml, csv, json
 * @class FileCreater
 * @package OlegTatarenko\Sitemap
 */
class FileCreater
{
    /** @var array $pages список страниц сайта в виде массива массивов с параметрами */
    private $pages = [[]];

    /** @var string $fileFormat тип файла, в котором будет записана карта сайта*/
    private $fileFormat;

    /** @var string $savePath путь для сохранения файла */
    private $savePath;

    /** @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @param string $fileFormat тип файла, в котором будет записана карта сайта
     * @param string $savePath путь для сохранения файла
     * @return string карта сайта в формате xml
     * */
    public function __construct($pages, $fileFormat, $savePath)
    {
        $this->pages = $pages;
        $this->fileFormat = strtolower($fileFormat);

        if (key_exists('dirname', pathinfo($savePath))){
            $dirName = pathinfo($savePath)['dirname'];
            $fileName = pathinfo($savePath)['filename'];
            $savePath = $dirName . '/' . $fileName . '.' . $this->fileFormat;
            $this->savePath = $savePath;
        } else {
            $fileName = pathinfo($savePath)['filename'];
            $savePath = './' . $fileName . '.' . $this->fileFormat;
            $this->savePath = $savePath;
        }
    }

    /**
     * Создаем файл с картой сайта
     *
     * @throws Exception
     */
        public function createFileWithSiteMap(): void
        {
        Validator::isValidFileFormat($this->fileFormat);
        Validator::isValidDateFormat($this->pages);
        Validator::isValidLenURL($this->pages);
        Validator::isValidChangeFreqs($this->pages);
        Validator::isValidPriority($this->pages);
        Validator::isDir($this->savePath);

        $methodsMapCreator = [
            'xml' => 'createXML',
            'csv' => 'createCSV',
            'json' => 'createJSON',
        ];
        $method = $methodsMapCreator[$this->fileFormat];

        $mapData = MapCreator::$method($this->pages);//отформатированная строка с картой сайта

        if(!file_put_contents($this->savePath, $mapData)){
            throw new Exception(sprintf('Не удалось создать файл по указанному пути: %s.', $this->savePath));
        }

        echo 'Файл успешно создан по адресу: ' . $this->savePath;
    }
}