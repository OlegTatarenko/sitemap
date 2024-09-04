<?php

namespace OlegTatarenko\Sitemap;

use OlegTatarenko\Sitemap\Creators\MapCreatorCSV;
use OlegTatarenko\Sitemap\Creators\MapCreatorJSON;
use OlegTatarenko\Sitemap\Creators\MapCreatorXML;
use OlegTatarenko\Sitemap\Exceptions\InvalidFileFormat;

/**
 * Фабричный класс для создания экземпляров MapCreator.
 * @class MapsCreatorFactory
 * @package OlegTatarenko\Sitemap
 */
class MapsCreatorFactory
{
    /**
     * @param string $fileFormat тип файла, в котором будет записана карта сайта.
     * @return MapsCreator экземпляр MapCreator, который реализует интерфейс MapsCreator.
     * @throws InvalidFileFormat
     */
    public static function creat(string $fileFormat): MapsCreator
    {
        return match ($fileFormat) {
            'xml' => new MapCreatorXML(),
            'csv' => new MapCreatorCSV(),
            'json' => new MapCreatorJSON(),
            default => throw new InvalidFileFormat(
                sprintf('InvalidFileFormat Exception: Указан недопустимый формат файла: %s. Допустимы следующие форматы: %s', $fileFormat, implode(', ', Validator::EXT))
            )
        };
    }
}