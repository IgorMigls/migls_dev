<?php
/**
 * Created by IsWin.
 * Site: http://iswin.ru
 * Date: 04.04.18
 * Time: 1:02
 */


namespace UL\Handlers;


use Bitrix\Main\Application;
use Bitrix\Main\Event;
use Bitrix\Sale\BasketItem;

class Basket
{
    public static function onBeforeElementSave(BasketItem $item)
    {
        /** @todo это конечно адов костыль, но я пока не могу понять почему он не сохраняет дробное количество */

        /**
         * очень странное поведение функции getQuantity там стоит явное преобразование
         * в float - но возвращает через запятую, а не точку, возможно поэтому и не сейвится нормально,
         * почему так происходит я теряюсь в догадках
         */
        $count = (float)$item->getQuantity();
        if ($count > 0 && $item->getId()) {
            $connection = Application::getConnection();
            $count = str_replace(',', '.', $count);
            $sql = "UPDATE b_sale_basket SET QUANTITY={$count} WHERE ID={$item->getId()}";
            $connection->query($sql);
        }
    }
}