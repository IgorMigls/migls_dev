<?php
namespace AB\FromIblock;
	/** @var \CBitrixComponent $this */
	/** @var array $arParams */
	/** @var array $arResult */
	/** @var string $componentPath */
	/** @var string $componentName */
	/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */

use AB\FormIblock\Helper;
use AB\FormIblock\Protect;
use AB\FormIblock\Result;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Iblock\PropertyTable;

Loader::includeModule('iblock');
Loader::includeModule('ab.form_iblock');

class FeedbackComponent extends \CBitrixComponent
{
	protected $USER;
	protected $selectProp;
	protected $Result;

	/**
	 * @param \CBitrixComponent|null $component
	 */
	public function __construct($component = null)
	{
		global $USER;
		parent::__construct($component);
		$this->USER = $USER;
		$this->Result = new Result();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param $arParams
	 *
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		if (intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		$fields = [];
		foreach (Helper::getIbFields() as $code => $item) {
			$fields[] = $code;
		}
		foreach ($arParams['FIELDS'] as $field) {
			if (!in_array($field, $fields)) {
				$this->selectProp[] = $field;
			} else {
				$this->arResult['FIELDS'][$field] = Helper::getIbFields($field);
				if (!empty($arParams['RENAME_' . $field])) {
					$this->arResult['FIELDS'][$field]['NAME'] = $arParams['RENAME_' . $field];
				}
				if (in_array($field, $arParams['REQUIRED']) || in_array(0, $arParams['REQUIRED'])) {
					$this->arResult['FIELDS'][$field]['REQUIRED'] = true;
				}
				switch ($field) {
					case 'PREVIEW_TEXT':
					case 'DETAIL_TEXT':
						$this->arResult['FIELDS'][$field]['PROPERTY_TYPE'] = 'TEXT';
						break;
				}
			}
		}

		if (strlen($arParams['FORM_ID']) == 0) {
			$arParams['FORM_ID'] = 'form_request';
		}
		if (strlen($arParams['BTN_SAVE']) == 0) {
			$arParams['BTN_SAVE'] = 'Отправить';
		}

		return $arParams;
	}

	/**
	 * @method getProperties
	 */
	public function getProperties()
	{
		if (count($this->selectProp) > 0) {
			$obProp = PropertyTable::getList([
				'select' => ['ID', 'NAME', 'CODE', 'IS_REQUIRED', 'PROPERTY_TYPE'],
				'filter' => ['IBLOCK_ID' => $this->arParams['IBLOCK_ID'], '@CODE' => $this->selectProp],
			]);
			while ($prop = $obProp->fetch()) {
				if (!empty($this->arParams['RENAME_' . $prop['CODE']])) {
					$prop['NAME'] = $this->arParams['RENAME_' . $prop['CODE']];
				}
				if (in_array($prop['CODE'], $this->arParams['REQUIRED']) || in_array(0, $this->arParams['REQUIRED'])) {
					$prop['REQUIRED'] = true;
				}
				if ($prop['IS_REQUIRED'] == 'Y')
					$prop['REQUIRED'] = true;

				$this->arResult['PROPERTIES'][$prop['CODE']] = $prop;
			}
		}
	}

	/**
	 * @method combineFields
	 */
	public function combineFields()
	{
		foreach ($this->arResult['PROPERTIES'] as $code => $arProp) {
			$this->arResult['FORM_FIELDS'][$code] = $arProp;
		}
		$this->arResult['FORM_FIELDS'] = array_merge($this->arResult['FORM_FIELDS'], $this->arResult['FIELDS']);
	}

	public function createSign()
	{
		$Protect = new Protect($this->arParams);

		return $Protect->sign();
	}

	public function action($action)
	{
		try {
			if (!is_callable(array($this, $action))) {
				throw new \Exception('Метод нельзя вызвать');
			}

			$this->Result->setData($this->$action());

		} catch (\Exception $e) {
			$this->Result->addError(new Error($e->getMessage()));
		}

		return $this;
	}

	/**
	 * @method getResult
	 * @return array
	 */
	public function getResult()
	{
		$result = ['errors' => null, 'data' => null, 'status' => 0];
		if ($this->Result->isSuccess()) {
			$result['status'] = 1;
			$result['data'] = $this->Result->getId();
		} else {
			$result['errors'] = $this->Result->getErrorMessages();
		}

		return $result;
	}

	public function save()
	{
		$this->Result->setId(123);
		return $this->request->getPostList()->toArray();
	}
	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		\CUtil::InitJSCore(['jq3', 'bootstrap', 'form_iblock']);

		$this->getProperties();
		$this->combineFields();
		$this->arResult['SS'] = urlencode($this->createSign());


		$this->includeComponentTemplate();
	}

}