<?php

namespace UL\Main\Import;

use Bitrix\Main\IO;
use Bitrix\Main\Application;
use Bitrix\Iblock;
use Bitrix\Main;

class AllCategory
{
	protected $iblock;
	protected $root;
	protected $folder;
	protected $Dir;
	protected $CIBlockSection;
	protected $arSections;
	protected $Result;

	private $bResort = false;

	/**
	 * AllCategory constructor.
	 *
	 * @param $iblock
	 * @param $folder
	 */
	public function __construct($iblock, $folder)
	{
		$this->iblock = $iblock;
		$this->folder = $folder;
		$this->root = Application::getDocumentRoot();

		$this->Dir = new IO\Directory($this->root.$this->folder);

		$this->CIBlockSection = new \CIBlockSection();
		$this->Result = new Result();
	}

	/**
	 * @method getDir - get param Dir
	 * @return IO\Directory
	 */
	public function getDir()
	{
		return $this->Dir;
	}

	/**
	 * @param string $directory
	 *
	 * @return array
	 */
	public function recursiveDirectoryIterator ($directory = null) {
		if(strlen($directory) == 0){
			$directory = $this->Dir->getPath();
		}

		return Helper::recursiveDirectoryIterator($directory);
	}

	protected function sectionByXmlId($xmlId)
	{
		return Iblock\SectionTable::getRow([
			'select' => ['ID'],
			'filter' => ['IBLOCK_ID' => $this->iblock, '=XML_ID'=>$xmlId],
		]);
	}

	public function saveSections($arSections, $parent = false)
	{


		foreach ($arSections as $k => $section) {
			$name = $section['NAME'];
			$xmlId = md5($name);
			$save = [
				'IBLOCK_ID' => $this->getIblock(),
				'NAME' => $name,
				'XML_ID' => $xmlId,
				'IBLOCK_SECTION_ID' => intval($parent) > 0 ? $parent : false
			];

			$arCheck = $this->sectionByXmlId($xmlId);
			$id = null;

			if(!is_null($arCheck)){
				$id = $arCheck['ID'];
			}
			$this->Result->setItemSuccess(true);
			$this->saveSection($id, $save);

			if(count($section['ITEMS']) > 0 && $this->Result->getItemSuccess()){
				$this->saveSections($section['ITEMS'], $this->Result->getId());
			}
		}

		$this->Result->setFinish(true);
	}

	public function saveSection($id = null, array $arSection)
	{
		if(intval($id) == 0){
			$id = $this->CIBlockSection->Add($arSection, $this->bResort);
			if(intval($id) == 0){
				$this->addError($this->CIBlockSection->LAST_ERROR, $arSection['NAME']);
			}
		} else {
			if(!$this->CIBlockSection->Update($id, $arSection, $this->bResort)){
				$this->addError($this->CIBlockSection->LAST_ERROR, $arSection['NAME']);
			}
		}

		$this->Result->setId($id);
	}

	public function run()
	{
		$this->arSections = $this->recursiveDirectoryIterator();
		$this->saveSections($this->arSections);

		if(!$this->Result->isSuccess()){
//			PR($this->Result->getErrorMessages());
		}

		if(!$this->bResort){
			$this->CIBlockSection->ReSort($this->iblock);
		}

		return $this;
	}


	/**
	 * @method getIblock - get param iblock
	 * @return mixed
	 */
	public function getIblock()
	{
		return $this->iblock;
	}

	private function addError($msg, $type)
	{
		$this->Result->addError(new Main\Error($type.' - '.strip_tags($msg)));
	}

	/**
	 * @method getResult - get param Result
	 * @return Result
	 */
	public function getResult()
	{
		return $this->Result;
	}
}