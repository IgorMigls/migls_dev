<?php
/**
 * Created by IsWin.
 * Site: http://iswin.ru
 * Date: 01.04.18
 * Time: 20:45
 */


namespace Ul\Main\Measure;
use Bitrix\Main;


class MeasureTable extends Main\Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_catalog_measure';
    }

    public static function getMap()
    {
        return array(
            'ID' => new Main\Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            'CODE' => new Main\Entity\TextField('CODE'),
            'MEASURE_TITLE' => new Main\Entity\TextField('MEASURE_TITLE'),
            'SYMBOL_RUS' => new Main\Entity\TextField('SYMBOL_RUS'),
            'SYMBOL_INTL' => new Main\Entity\TextField('SYMBOL_INTL'),
            'SYMBOL_LETTER_INTL' => new Main\Entity\TextField('SYMBOL_LETTER_INTL'),
            'IS_DEFAULT' => new Main\Entity\TextField('IS_DEFAULT')
        );
    }
}