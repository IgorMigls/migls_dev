<?php
namespace UL\Main\Admins;
use DigitalWand\AdminHelper\Helper\AdminEditHelper;
use Ul\Main\Map\Model\CordTable;
//CordTable::dropTables();
//CordTable::createTable();

class MapEditHelper extends AdminEditHelper
{
	protected static $model = '\UL\Main\Map\Model\CordTable';
}