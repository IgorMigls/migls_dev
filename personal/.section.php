<?
$sSectionName = "Мой кабинет";
$arDirProperties = array(

);
global $USER;
if(!$USER->IsAuthorized())
	LocalRedirect('/');
?>