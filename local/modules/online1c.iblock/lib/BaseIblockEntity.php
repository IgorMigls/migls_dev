<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 26.04.2017
 */

namespace Online1c\Iblock;

use Bitrix\Main\Entity;

class BaseIblockEntity
{
	private $iblockId = null;

	/** @var  Entity\Base */
	private $entityElement;


	public function __construct($iblockId)
	{
		$this->iblockId = (int)$iblockId;

		if ($this->iblockId == 0)
			throw new IblockException('iblock is null');

	}


	public function compile()
	{
		$name = 'IblockElement'.$this->iblockId;
		if(Entity\Base::isExists(__NAMESPACE__.'\\'.$name)){
			$this->entityElement = Entity\Base::getInstance(__NAMESPACE__.'\\'.$name);
		} else {
			$this->entityElement = Entity\Base::compileEntity(
				$name, Element::getMap(), [
					'namespace' => __NAMESPACE__,
					'table_name' => Element::getTableName()
				]
			);
		}

		return $this;
	}

	/**
	 * @method getEntityElement - get param entityElement
	 * @return Entity\Base
	 */
	public function getEntityElement()
	{
		return $this->entityElement;
	}

}