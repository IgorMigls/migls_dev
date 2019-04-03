<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.01.2017
 */

namespace UL\Main\Import;

use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::includeModule('catalog');

class Prices
{
	protected $shopId = null;
	protected $iblockId = null;
	protected $catalogInfo = [];

	/**
	 * Prices constructor.
	 *
	 * @param $shopId
	 * @param $iblockId
	 *
	 * @throws \Exception
	 */
	public function __construct($shopId, $iblockId)
	{
		$shopId = intval($shopId);
		$iblockId = intval($iblockId);

		if($shopId == 0){
			throw new \Exception('shop id is null');
		}
		if($iblockId == 0){
			throw new \Exception('iblock id is null');
		}

		$this->setShopId($shopId);
		$this->setIblockId($iblockId);
		$this->setCatalogInfo(\CCatalogSku::GetInfoByOfferIBlock($this->iblockId));
	}

	/**
	 * @method getShopId - get param shopId
	 * @return int|null
	 */
	public function getShopId()
	{
		return $this->shopId;
	}

	/**
	 * @param int|null $shopId
	 *
	 * @return Prices
	 */
	public function setShopId($shopId)
	{
		$this->shopId = $shopId;

		return $this;
	}

	/**
	 * @method getIblockId - get param iblockId
	 * @return int|null
	 */
	public function getIblockId()
	{
		return $this->iblockId;
	}

	/**
	 * @param int|null $iblockId
	 *
	 * @return Prices
	 */
	public function setIblockId($iblockId)
	{
		$this->iblockId = $iblockId;

		return $this;
	}

	/**
	 * @method getCatalogInfo - get param catalogInfo
	 * @return array|mixed
	 */
	public function getCatalogInfo()
	{
		return $this->catalogInfo;
	}

	/**
	 * @param array|mixed $catalogInfo
	 *
	 * @return Prices
	 */
	public function setCatalogInfo($catalogInfo)
	{
		$this->catalogInfo = $catalogInfo;

		return $this;
	}


}