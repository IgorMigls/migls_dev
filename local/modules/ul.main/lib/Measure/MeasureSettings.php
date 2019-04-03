<?php
/**
 * Created by IsWin.
 * Site: http://iswin.ru
 * Date: 01.04.18
 * Time: 20:52
 */


namespace Ul\Main\Measure;


class MeasureSettings
{

    const MEASURE_PROP_CODE = 'MEASURE';
    const MEASURE_RATIO_PROP_CODE = 'MULTIPLICITY_MEASURE';

    protected static $cache = array();

    public static function getAllMeasures()
    {
        if (isset(self::$cache['all-measures'])) {
            return self::$cache['all-measures'];
        }

        $ret = array();
        $rows = MeasureTable::query()
            ->addSelect('*')
            ->exec();

        while ($row = $rows->fetch()) {
            $ret[$row['ID']] = $row;
        }

        return self::$cache['all-measures'] = $ret;
    }

    /** @todo вынести в класс ProductMeasure */

    public static function getMeasureById($id)
    {
        return self::getAllMeasures()[$id];
    }

    public static function getDefaultMeasure()
    {
        if (isset(self::$cache['DEFAULT_MEASURE'])) {
            return self::$cache['DEFAULT_MEASURE'];
        }

        $row = MeasureTable::query()
            ->addSelect('*')
            ->addFilter('=IS_DEFAULT', 'Y')
            ->exec()
            ->fetch();

        return self::$cache['DEFAULT_MEASURE'] = $row;
    }


    public static function getDefaultMeasureRatio()
    {
        return 1;
    }

    public static function getMeasurePropCode()
    {
        return self::MEASURE_PROP_CODE;
    }

    public static function getMeasureRatioPropCode()
    {
        return self::MEASURE_RATIO_PROP_CODE;
    }
}