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
    private array $pages;

    /** @var string $fileFormat тип файла, в котором будет записана карта сайта */
    private string $fileFormat;

    /** @var string $savePath путь для сохранения файла */
    private string $savePath;

    /**
     * @param array $pages список страниц сайта в виде массива массивов с параметрами
     * @param string $fileFormat тип файла, в котором будет записана карта сайта
     * @param string $savePath путь для сохранения файла
     */
    public function __construct(array $pages, string $fileFormat, string $savePath)
    {
        $this->pages = $pages;
        $this->fileFormat = strtolower($fileFormat);
        $this->savePath = $this->getCorrectPath($savePath);
    }

    /**
     * Создаем файл с картой сайта
     *
     * @throws Exception
     */
        public function createFileWithSiteMap(): void
        {
            Validator::isEmptyPages($this->pages);
            Validator::isExistLoc($this->pages);
            Validator::isValidFileFormat($this->fileFormat);
            Validator::isValidDateFormat($this->pages);
            Validator::isValidLenURL($this->pages);
            Validator::isValidChangeFreqs($this->pages);
            Validator::isValidPriority($this->pages);
            Validator::isDir($this->savePath);

            $mapCreator = MapsCreatorFactory::creat($this->fileFormat);//объект конкретного класса, создаваемый фабрикой в зависимости от формата файла
            $mapData = $mapCreator->createMap($this->pages);//отформатированная строка с картой сайта

            if(!file_put_contents($this->savePath, $mapData)) {
                throw new Exception(sprintf('Не удалось создать файл по указанному пути: %s.', $this->savePath));
            }

            echo 'Файл успешно создан по адресу: ' . $this->savePath;
        }

        private function getCorrectPath(string $savePath): string
        {
            if (key_exists('dirname', pathinfo($savePath))) {
                $dirName = pathinfo($savePath)['dirname'];
                if (pathinfo($savePath)['dirname'] == '.') {
                    $dirName = 'map';
                }
                $fileName = pathinfo($savePath)['filename'];
                $savePath = $dirName . '/' . $fileName . '.' . $this->fileFormat;
            } else {
                $fileName = pathinfo($savePath)['filename'];
                $savePath = './map/' . $fileName . '.' . $this->fileFormat;
            }
            return $savePath;
        }
}