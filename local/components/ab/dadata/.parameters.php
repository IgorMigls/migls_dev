<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$types = [
	'address' => Loc::getMessage('AB_DADATA_PARAM_TYPE_ADDRESS'),
	'company' => Loc::getMessage('AB_DADATA_PARAM_TYPE_COMPANY'),
	'bank' => Loc::getMessage('AB_DADATA_PARAM_TYPE_BANK'),
	'fio' => Loc::getMessage('AB_DADATA_PARAM_TYPE_FIO'),
	'email' => 'Email'
];

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"KEY" => array(
			"NAME" => Loc::getMessage('AB_DADATA_PARAM_KEY')
		),
		"INCLUDE_UI_SCRIPT" => array(
			"NAME" => Loc::getMessage('AB_DADATA_PARAM_INCLUDE_UI_SCRIPT'),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		'TYPE' => array(
			'NAME' => Loc::getMessage('AB_DADATA_PARAM_TYPE'),
			'TYPE' => 'LIST',
			'VALUES' => $types,
			'DEFAULT' => 'address'
		)
	),
);