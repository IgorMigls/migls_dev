<?php
namespace UL\Import;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use PW\Tools\Debug;
use Soft\Element;

Loader::includeModule('iblock');
Loader::includeModule('soft.iblock');

class MainIblockImport
{
	const PREF_UPDATE = 'IMPORT_UPDATE';
	const PREF_ADD = 'IMPORT_ADD';
	const PREF_ERROR = 'IMPORT_ERROR';

	protected $CIBlockElement;
	protected $newProducts = 0;
	protected $updated = 0;
	protected $errors = [];

	public function __construct()
	{
		$this->CIBlockElement = new \CIBlockElement();
	}

	public function checkElement($data = [])
	{
		return \Bitrix\Iblock\ElementTable::getRow([
			'select' => ['ID'],
			'filter' => [
				'IBLOCK_ID'=>$data['IBLOCK_ID'],
				'=CODE'=>$data['CODE']
			]
		]);
	}

	public function saveElement($data = [])
	{
		$row = $this->checkElement($data);
		if(!is_null($row)){
			$this->CIBlockElement->Update($row['ID'], $data);
			$this->addUpdated();
		} else {
			$this->CIBlockElement->Add($data);
			$this->addNewProducts();
		}
	}

	/**
	 * @method getNewProducts - get param newProducts
	 * @return int
	 */
	public function getNewProducts()
	{
		return $_SESSION[self::PREF_ADD];
	}

	/**
	 * @method getUpdated - get param updated
	 * @return int
	 */
	public function getUpdated()
	{
		return $_SESSION[self::PREF_UPDATE];
	}

	/**
	 * @method getErrors - get param errors
	 * @return array
	 */
	public function getErrors()
	{
		return $_SESSION[self::PREF_ERROR];
	}

	/**
	 * @method addUpdated - add param Updated
	 */
	public function addUpdated()
	{
		$_SESSION[self::PREF_UPDATE]++;
	}

	/**
	 * @method addErrors - add param Errors
	 */
	public function addErrors()
	{
		$_SESSION[self::PREF_ERROR]++;
	}

	/**
	 * @method addNewProducts - add param NewProducts
	 */
	public function addNewProducts()
	{
		$_SESSION[self::PREF_ADD]++;
	}

	/**
	 * @method resetSession
	 * @return $this
	 */
	public function resetSession()
	{
		$_SESSION[self::PREF_ADD] = 0;
		$_SESSION[self::PREF_ERROR] = 0;
		$_SESSION[self::PREF_UPDATE] = 0;

		return $this;
	}

	public function deActiveElements($IBLOCK_ID, $shopId){
		$connect = Application::getConnection();
		$tbl = \Bitrix\Iblock\ElementTable::getTableName();

		$sql = "UPDATE ".$tbl." BE
			LEFT JOIN b_iblock_element_prop_s".$IBLOCK_ID." BP ON (BE.ID = BP.IBLOCK_ELEMENT_ID)
			SET ACTIVE = 'N'
			WHERE BE.IBLOCK_ID = ".$IBLOCK_ID." AND BP.PROPERTY_48 = ".$shopId;

		$connect->query($sql);
	}
}