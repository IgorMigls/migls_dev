<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("AB_DA_DATA_NAME"),
	"DESCRIPTION" => GetMessage("AB_DA_DATA_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "abra",
		"NAME" => GetMessage("AB_DA_DATA_CATEGORY"),
		"SORT" => 10,
	),
);
?>