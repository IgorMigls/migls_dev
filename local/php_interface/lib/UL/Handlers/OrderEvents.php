<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 09.06.2018
 */

namespace UL\Handlers;

use Bitrix\Main;
use Bitrix\Sale;
use UL\Main\Basket\Model\BasketShopTable;
use UL\Main\Delivery\ComplectationTable;
use UL\Main\Personal\OrderNumberTable;


class OrderEvents
{
	/**
	 * @method OnSaleOrderDeleted
	 * @param Main\Event $event
	 */
	public static function OnSaleOrderDeleted(Main\Event $event)
	{
		/** @var Sale\Order $order */
		$order = $event->getParameter("ENTITY");
		$order->getId();

		/** @var Sale\Basket $basketCollection */
		if ($basketCollection = $order->getBasket())
		{
			/** @var Sale\BasketItem $basketItem */
			foreach ($basketCollection as $basketItem)
			{
				BasketShopTable::deleteByBitrixId($basketItem->getId());
			}
		}

		OrderNumberTable::deleteByOrderId($order->getId());
		ComplectationTable::deleteByOrderId($order->getId());
	}
}