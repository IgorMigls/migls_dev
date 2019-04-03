<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Магазин',
	"DESCRIPTION" => '',
	"ICON" => "/images/catalog.gif",
	"COMPLEX" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "ul",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => 'Каталоги',
			"SORT" => 30,
		)
	)
);
?>