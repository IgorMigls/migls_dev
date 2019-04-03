<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 09.08.2017
 */

namespace AB\Tools\Console\Scripts;

use AB\Tools\Console\ProgressBar;
use Bitrix\Main;
use Esd\HL\MainTable;
use Online1c\Iblock\Element;
use Bitrix\Highloadblock\HighloadBlockTable as HL;

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('highloadblock');


class SetTags implements IConsole
{
	/**
	 * @var array - массив параметров CLI
	 */
	protected $params;

	/**
	 * Creator constructor. В конструктор приходят все параметры из CLI
	 *
	 * @param array $params
	 */
	public function __construct($params = [])
	{
		global $argv;

		if (count($params) == 0 || is_null($params)){
			$this->params = $argv;
		}

		$this->params = $params;
	}

	/**
	 * @method description - недольшое описание комнады: для чего, для кого и пр.
	 * @return string
	 */
	public function description()
	{
		return 'add description';
	}

	/**
	 * @method getParams - get param params
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @method setParams - set param Params
	 * @param array $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

	public function run($params)
	{
		$products = Element::getList([
			'select' => [
				'ID', 'NAME', 'TAGS',
				'BRAND' => 'PROPERTY.BRAND',
				'SIZE' => 'PROPERTY.SIZE',
				'COLOR' => 'PROPERTY.COLOR',
				'MATERIAL' => 'PROPERTY.MATERIAL'
			],
			'filter' => [
				'IBLOCK_ID' => 2,
			],
			'limit' => null,
		])->fetchAll();

		$BrandBlock = HL::compileEntity(HL::getRowById(6))->getDataClass();
		$ColorBlock = HL::compileEntity(HL::getRowById(1))->getDataClass();

		$CElement = new \CIBlockElement();

		$cnt = count($products);
		$Bar = new ProgressBar();
		$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $cnt);
		$i = 0;
		foreach ($products as $item) {
			$i++;
			$Bar->update($i);
			if(strlen($item['TAGS']) > 0)
				continue;

			$arBrand = $BrandBlock::getRow(['filter' => ['=UF_XML_ID' => $item['BRAND']]]);
			$arColor = $ColorBlock::getRow(['filter' => ['=UF_XML_ID' => $item['COLOR']['VALUE'][0]]]);
			$material = implode(',', $item['MATERIAL']['VALUE']);

			$arTags = [
				$arBrand['UF_NAME'],
				$arColor['UF_NAME'],
				$item['SIZE']['VALUE'][0].' размер',
				$material
			];

			$CElement->Update($item['ID'], ['TAGS' => implode(',', $arTags)]);
		}
	}

}